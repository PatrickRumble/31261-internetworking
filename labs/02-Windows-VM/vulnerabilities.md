# Windows VM Vulnerabilities

This document highlights the deliberate vulnerabilities included in the Windows target VM. Each vulnerability is presented with a clear **Description**, **Educational Purpose**, **Real-World Relevance**, an **Example**, and **Mitigations (brief)** — matching the structure used for the Linux vulnerabilities document.

---

## Remote Desktop Protocol (RDP) / Remote Code Execution (RCE)

**Description:**  
**Remote Desktop Protocol (RDP)** provides graphical remote access to Windows systems. When RDP is misconfigured (for example: NLA disabled, weak credentials, exposed port 3389, or missing patches), it can be abused to gain **remote code execution (RCE)** or full control of the target. Historical vulnerabilities (e.g., *BlueKeep*) show how unpatched RDP stacks can be weaponised.

**Educational Purpose:**  
Students will learn how to discover exposed RDP endpoints, assess authentication and configuration weaknesses, and observe how access can lead to post-exploitation activities. Exercises are designed to show both offensive techniques (scanning, brute force, session access) and defensive controls (NLA, account lockouts, patching, MFA).

**Real-World Relevance:**  
RDP is a frequent initial access vector for ransomware and targeted intrusions. Unpatched or internet-exposed RDP services are regularly scanned and attacked by opportunistic actors. Securing RDP is critical to preventing high-impact compromises in enterprise and cloud environments.

**Example:**  
An attacker scans the subnet, finds TCP/3389 open on a host with NLA disabled, brute-forces an Administrator account with a weak password, and gains an interactive desktop session. From the session, the attacker runs commands and deploys tools.

```bash
# scanning example (from attacker VM)
nmap -Pn -p 3389 --open 192.168.56.0/24

# connect example (from Kali)
xfreerdp /v:192.168.56.102 /u:Administrator
```

**Mitigations (brief):**

* Enable **Network Level Authentication (NLA)**.  
* Restrict RDP to trusted hosts or require VPN access.  
* Enforce strong passwords and account lockout policies.  
* Apply regular Windows security updates and minimize exposed RDP surfaces.  
* Use MFA for remote access and monitor RDP logs for anomalous activity.

---

## Unquoted Service Path

**Description:**  
An **unquoted service path** occurs when a Windows service binary path contains spaces but is not enclosed in quotation marks. The Service Control Manager may parse the path and attempt to execute earlier segments (e.g., `C:\Program.exe`) if present — allowing an attacker who can place a binary at that location to execute code with the service’s privileges (often SYSTEM).

**Educational Purpose:**  
Students will identify unquoted service paths using `sc qc` and PowerShell, reproduce the issue in a safe VM, observe privilege escalation using harmless payloads, and remediate the configuration. The exercise demonstrates how minor deployment mistakes can enable full local privilege escalation.

**Real-World Relevance:**  
Unquoted service paths are a common legacy misconfiguration found in poorly packaged or old software. Attackers often exploit them during post-compromise to gain SYSTEM privileges. Regular audits, secure packaging, and correct install scripts prevent this issue.

**Example:**  
A vulnerable service is configured with `BINARY_PATH_NAME : C:\Program Files\Test Service\service.exe`. An attacker places `C:\Program.exe` (a malicious binary). When the service starts, Windows executes `C:\Program.exe` instead of the intended binary — running attacker code as the service account.

```powershell
# example steps (run in elevated CMD/PowerShell inside lab VM)
mkdir "C:\Program Files\Test Service"
copy "%WINDIR%\System32\calc.exe" "C:\Program Files\Test Service\service.exe" /Y
copy "%WINDIR%\System32\calc.exe" "C:\Program.exe" /Y

sc create VulnerableTest binPath= "C:\Program Files\Test Service\service.exe" start= auto
sc qc VulnerableTest
sc start VulnerableTest

# Remediate by quoting the path:
sc config VulnerableTest binPath= "\"C:\Program Files\Test Service\service.exe\""
sc qc VulnerableTest
```

**Mitigations (brief):**

* Always quote service binary paths that contain spaces.  
* Audit services (`sc qc`, `Get-WmiObject Win32_Service`) to find unquoted entries.  
* Restrict write permissions to system directories (e.g., `C:\`, `C:\Program Files`).  
* Use secure installers and configuration baselines to prevent deployment mistakes.

---

## NTLM / SMB Relay

**Description:**  
**NTLM** (NT LAN Manager) and **SMB** (Server Message Block) support authentication and file sharing in Windows networks. Legacy name-resolution protocols such as **LLMNR** and **NBT-NS** can be spoofed by an attacker, causing clients to authenticate to attacker-controlled hosts. Captured NTLM challenge/response data can be cracked offline or — more dangerously — **relayed** to other services to impersonate the user.

**Educational Purpose:**  
Students will capture and relay NTLM authentication in an isolated lab using tools like **Responder** and **ntlmrelayx**, learning how automatic name resolution and legacy authentication are abused. Defensive exercises cover disabling LLMNR/NBT-NS, enforcing SMB signing, and migrating to Kerberos.

**Real-World Relevance:**  
NTLM relay and LLMNR poisoning are practical techniques used in lateral movement and privilege escalation. They have been leveraged in real intrusions to obtain domain credentials or to move laterally within an environment. Removing legacy protocols and enforcing secure authentication reduces this attack surface significantly.

**Example (scenario):**  

1. A user types `\\ManagerServer\payslips\NovemberPayslip` but mistypes the hostname.  
2. The client falls back to LLMNR/NBT-NS to resolve the name.  
3. An attacker’s machine replies and receives an NTLMv2 challenge/response.  
4. The attacker relays the authentication to another host to gain access or cracks the hash offline.

```bash
# Attacker (Kali) examples — lab only
# Run Responder to capture LLMNR/NBT-NS/NetBIOS hashes
sudo responder -I eth0 -wrf

# Relay captured hashes to an SMB target (lab-only, use responsibly)
ntlmrelayx.py -t smb://192.168.56.102 -smb2support
```

**Mitigations (brief):**

* Disable **LLMNR** and **NBT-NS** via Group Policy.  
* Enforce **SMB signing** and prefer **Kerberos** authentication.  
* Restrict and monitor NTLM usage; disable NTLMv1 entirely.  
* Segment networks and reduce unnecessary SMB exposure.  
* Monitor authentication logs for unusual or repeated NTLM events.

---

## Key Takeaways

* Secure remote access: enable NLA, use MFA, restrict RDP to trusted networks, and patch promptly.  
* Prevent local privilege escalation: always quote service paths and audit service configurations.  
* Eliminate or restrict legacy protocols (LLMNR, NBT-NS, NTLMv1); enforce SMB signing and Kerberos.  
* Apply least privilege to services and file system permissions; restrict write access to system locations.  
* Monitor logs and network activity for suspicious authentication and service behaviour.  
* Practice offensive and defensive controls in isolated lab environments before applying changes to production.

---
