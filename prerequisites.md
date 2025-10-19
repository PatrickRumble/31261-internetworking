## Prerequisites

Before starting the labs, ensure you have the following installed and configured.

## 🖥️ System requirements
- Host machine: **8 GB RAM minimum** (16 GB recommended)  
- Disk: **≥ 80 GB free**  
- CPU with virtualization support (Intel VT-x or AMD-V)  
- Host OS: Linux, macOS or Windows 10/11

## 🔧 Required software
- VirtualBox **or** VMware Workstation Player  
- VirtualBox Extension Pack (if using VirtualBox)  
- (Optional) Vagrant for automated provisioning

## 📦 Virtual machines in this lab
- **Linux VM** — target (web & DB services)  
- **Windows VM** — target (file/remote services)  
- **Kali VM** — attacker (use this VM to run scans and connect to targets)

VM images & configuration files are supplied by the instructor.

---

## 🌐 Networking & connecting the VMs (simple, reliable steps)

### Assumption
VMs are attached to the same **host-only** or **internal** network and obtain addresses via **DHCP**. This isolates the lab network from the Internet while allowing VMs to talk to each other.

### Confirm adapter type (quick)
**VirtualBox (GUI)**  
- VM → *Settings* → *Network* → Adapter → choose **Host-only Adapter** (or *Internal Network*).  
- Check **File → Host Network Manager** to ensure the host-only network exists and DHCP is enabled.

**VMware (GUI)**  
- VM → *Settings* → *Network Adapter* → choose **Host-only**.  
- Use *Virtual Network Editor* to confirm DHCP for the host-only network.

---

### How to discover each VM IP (pick one method)

#### 1) From inside the VM (most reliable)
- **Linux (inside VM)**  
```bash
ip -4 addr show           # look for the inet line for the host-only interface
# or concise:
ip -4 addr show | grep -oP '(?<=inet\s)\d+(\.\d+){3}'
```
- **Windows (inside VM)**  
Open PowerShell or CMD:
```powershell
ipconfig
# or (PowerShell)
Get-NetIPAddress -AddressFamily IPv4 | Format-Table IPAddress, InterfaceAlias
```

#### 2) From the Kali VM (recommended workflow for students)
Run a network discovery/ping-scan on the host-only subnet:
```bash
# replace the subnet with your host-only network if different
nmap -sn 192.168.56.0/24
arp -n                # shows MAC ⇄ IP mappings after a scan
```
`nmap -sn` lists active hosts quickly and is the fastest way to find the targets.

#### 3) From the host machine (if needed)
```bash
# Linux/macOS or Windows host
arp -a
```
VirtualBox also supports querying guest properties (requires Guest Additions):
```bash
VBoxManage guestproperty get "VM Name" "/VirtualBox/GuestInfo/Net/0/V4/IP"
```

---

### Verify connectivity & basic service checks
Once you have an IP:

```bash
# replace <IP> with the discovered address
ping -c 3 <IP>

# quick port scan for common services from Kali
nmap -Pn -sT -p 22,80,139,445,3389 <IP>
```

Expected: `ping` replies and `nmap` lists open ports you will use in labs (SSH, HTTP, SMB, RDP, etc).

---

### How to connect (examples)
- **SSH (Linux target)**  
```bash
ssh <user>@<IP>
```
- **Web (open in Kali browser)**  
```
http://<IP>/
```
- **RDP (Windows target) from Kali)**  
```bash
xfreerdp /v:<IP> /u:<username>
# or use Remmina GUI
```
- **SMB (list shares)**  
```bash
smbclient -L //<IP> -U '<username>'
```

> Replace `<user>`, `<username>` and `<IP>` with the values supplied by your instructor.

---

### Quick workflow (copy-paste starter for Kali)
```bash
# find hosts on the host-only network
nmap -sn 192.168.56.0/24

# test a candidate host
ping -c 3 192.168.56.101
nmap -Pn -sT -p 22,80,139,445,3389 192.168.56.101

# connect (examples)
ssh student@192.168.56.101
xfreerdp /v:192.168.56.102 /u:Administrator
```

---

### Troubleshooting (most common causes)
1. **Wrong adapter type:** double-check VM network is *Host-only* / *Internal*.  
2. **Different host-only networks:** ensure all VMs use the same host-only adapter name and subnet.  
3. **DHCP disabled on host-only:** enable DHCP in VirtualBox Host Network Manager or VMware Virtual Network Editor.  
4. **Firewall blocking ICMP/ports:** firewalls on targets may block ping; use `nmap -Pn` to check ports.  
5. **Network service down in VM:** restart the network:  
```bash
# Linux guest
sudo systemctl restart NetworkManager
# or request DHCP again
sudo dhclient -v <interface>
```
6. **VM corrupted:** restore the `*-baseline` snapshot and retry.

---

## Instructor notes & best practice
- Provide a single `labs/common/scripts/find-targets.sh` script that runs `nmap -sn` and neatly prints results — reduces student confusion.  
- Do **not** put real credentials in the public repo; use `creds.example` placeholders or distribute credentials via the LMS or instructor VM.  
- Add a short Troubleshooting checklist to the top of each lab for fast self-resolution.

---

Once networking is confirmed, proceed to `labs/00-introduction/overview.md` and follow the Quick Start there.
