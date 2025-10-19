# Windows VM Vulnerabilities

This document highlights the deliberate vulnerabilities included in the Windows target VM. Each vulnerability is presented with a clear description, educational purpose, real-world relevance, and an example demonstrating the practical impact.

---

## Remote Desktop Protocol (RDP) / Remote Code Execution (RCE)

**Description:**  
The **Remote Desktop Protocol (RDP)** enables remote users to connect to a Windows system with a graphical session. When RDP is misconfigured (for example: NLA disabled, weak credentials, exposed port 3389, or missing patches), it can be abused to achieve **remote code execution (RCE)** or full system compromise. Historic vulnerabilities such as *BlueKeep* (CVE-2019-0708) illustrate the severe impact of unpatched RDP stacks.

**Educational Purpose:**  
Students will learn how exposed RDP endpoints are identified, how weak configuration and credentials can be abused, and how attackers move from RDP access to post-exploitation. Defensive exercises focus on enforcing NLA, using strong authentication (MFA), restricting RDP exposure, and monitoring authentication logs.

**Real-World Relevance:**  
RDP is a frequent initial access vector for ransomware and intrusions. Unpatched or internet-exposed RDP services are regularly targeted by opportunistic and targeted attackers. Securing RDP is critical for preventing high-impact breaches in enterprise and cloud environments.

**Example:**  
An attacker scans the subnet, finds a host with TCP/3389 open and NLA disabled, and uses an RDP client (or brute-forcing tool) to obtain a remote desktop session. From the session, the attacker runs commands, installs tools, and attempts privilege escalation.

**Example commands (scanning & connection):**
<pre><code class="language-bash">
# From attacker (Kali) - quick scan
nmap -Pn -p 3389 --open 192.168.56.0/24

# Connect from Linux using xfreerdp
xfreerdp /v:192.168.56.102 /u:Administrator

# Or from Windows host (RDP client)
mstsc /v:192.168.56.102
</code></pre>

**Mitigations (brief):**

- Enable **Network Level Authentication (NLA)**.  
- Restrict RDP to trusted IPs or require VPN access.  
- Enforce strong passwords and account lockout policy.  
- Apply regular patching and reduce exposed RDP surfaces.  
- Enable MFA for remote sessions and monitor event logs for anomalies.  

---

## Unquoted Service Path Vulnerability

**Description:**  
An **unquoted service path** appears when a Windows service executable path contains spaces and is not enclosed in quotation marks. The Service Control Manager may interpret the path in chunks and attempt to execute earlier path segments (e.g., `C:\Program.exe`) before the intended binary, allowing an attacker who can write to those locations to execute code with the service’s privileges (often SYSTEM).

**Educational Purpose:**  
Students will identify unquoted service paths (using `sc qc` and PowerShell), reproduce the issue in a controlled VM (using harmless binaries), and remediate it by quoting the binary path. The exercise demonstrates how trivial deployment mistakes can lead to full local privilege escalation.

**Real-World Relevance:**  
Many legacy installers and badly packaged applications contain unquoted paths. Attackers frequently exploit these during local privilege escalation in post-compromise scenarios. Regular auditing and secure packaging practices eliminate this class of vulnerability.

**Reproducible lab steps (concise):**
<pre><code class="language-powershell">
# (Run in an elevated Command Prompt or PowerShell inside the Windows lab VM)

REM create test folder and place proof payload (harmless calc.exe copy)
mkdir "C:\Program Files\Test Service"
copy "%WINDIR%\System32\calc.exe" "C:\Program Files\Test Service\service.exe" /Y
copy "%WINDIR%\System32\calc.exe" "C:\Program.exe" /Y

REM create a service using the unquoted path (example)
sc create VulnerableTest binPath= "C:\Program Files\Test Service\service.exe" DisplayName= "Vulnerable Test Service" start= auto

REM verify the binary path (shows unquoted path)
sc qc VulnerableTest

REM start the service (will attempt to run C:\Program.exe first)
sc start VulnerableTest

REM remediation (quote the path)
sc config VulnerableTest binPath= "\"C:\Program Files\Test Service\service.exe\""
sc qc VulnerableTest
</code></pre>

**Mitigations (brief):**

- Quote executable paths for services with spaces.  
- Audit installed services periodically (`Get-WmiObject Win32_Service` or `sc qc`).  
- Restrict write permissions on system directories (root of C:\, Program Files).  
- Use secure installers and configuration baselines to prevent misconfiguration.  

---

## NTLM / SMB Relay Attack

**Description:**  
**NTLM** (NT LAN Manager) authentication and **SMB** (Server Message Block) are common Windows networking mechanisms. When legacy name-resolution services like **LLMNR** and **NBT-NS** are active, an attacker can spoof name resolution responses, capture NTLM challenge/response hashes, and **relay** them to authenticate against other services (NTLM relay). This lets attackers impersonate users without knowledge of plaintext passwords.

**Educational Purpose:**  
Students will use tools such as **Responder** and **ntlmrelayx** to capture and relay authentication attempts in a lab environment. The exercise demonstrates how automatic name-resolution and legacy authentication can be abused for lateral movement, and how mitigations like SMB signing and Kerberos-only configurations prevent such attacks.

**Real-World Relevance:**  
NTLM relay and LLMNR/NBT-NS poisoning are well-known techniques attackers use to escalate privileges and move laterally inside Windows networks. Real-world incidents often involve these methods to obtain domain-level access. Eliminating legacy protocols and enforcing secure authentication reduces this attack surface.

**Example scenario (simplified):**

1. A user attempts to access `\\ManagerServer\payslips` but mistypes the hostname.  
2. The client broadcasts an LLMNR/NBT-NS query on the local network.  
3. The attacker responds and captures the NTLMv2 challenge-response.  
4. The attacker either cracks the hash offline or relays it to another service to authenticate as the victim.

**Example commands (attacker side):**
<pre><code class="language-bash">
# On attacker (Kali) - run Responder to capture poisoning responses
sudo responder -I eth0 -wrf

# Use ntlmrelayx to relay captured hashes to target services
# (example usage; refer to tool docs and perform only in lab)
ntlmrelayx.py -t smb://192.168.56.102 -smb2support
</code></pre>

**Mitigations (brief):**

- Disable **LLMNR** and **NBT-NS** via Group Policy.  
- Enforce **SMB signing** and prefer **Kerberos** for authentication.  
- Restrict NTLM usage, disable NTLMv1, and monitor authentication logs.  
- Segment networks and limit unnecessary SMB exposure.  

---

## Key Takeaways

* Enforce secure remote access: enable NLA, require MFA, restrict RDP to trusted networks, and patch regularly.  
* Prevent trivial privilege escalation: always quote service paths and audit installed services.  
* Eliminate legacy, insecure name-resolution and authentication protocols (LLMNR, NBT-NS, NTLMv1); prefer Kerberos and SMB signing.  
* Apply the principle of least privilege across services, accounts, and filesystem permissions.  
* Monitor logs for anomalous authentication or service behaviour, and maintain a fast patch cadence.  
* Test both offensive and defensive controls in an isolated lab before applying changes in production.

---
