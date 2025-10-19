# 🔄 Reset / Restore to Baseline

This guide restores lab VMs to their **baseline configuration** using snapshots or OVA re-imports — and **does not rely on static IPs**.  


> ⚠️ Only perform these steps on lab VMs. Do **not** run on production systems. Ensure each VM is powered off before restoring or re-importing.

---

## ✅ Before you start
- Power off the VM you plan to restore.  
- Locate the baseline **snapshot** (recommended) or the baseline **OVA** (Report Appendix).  
- Confirm the host-only/internal network exists and DHCP is enabled (see `prerequisites.md`).  
- Keep instructor-provided credentials or notes handy (do **not** store them publicly).

---

## 🧰 Option 1 — Restore Snapshot (VirtualBox)

### GUI
1. Open **VirtualBox**, select the VM, open **Snapshots**.  
2. Select the `baseline` snapshot and click **Restore**.  
3. Start the VM.

### CLI
```bash
VBoxManage snapshot "VM Name" restore "baseline"
```
(Replace `"VM Name"` with the VM's actual name. Ensure the VM is powered off before restoring.)

---

## 🧾 Option 2 — Re-import OVA (if snapshot missing)

1. Remove the broken VM (optional):  
   ```bash
   VBoxManage unregistervm "VM Name" --delete
   ```
2. Import the baseline OVA:
   ```bash
   VBoxManage import /path/to/baseline.ova
   ```
3. Confirm the VM’s network adapter is set to **Host-only** / **Internal** as required by the lab.  
4. Start the VM.

---

## 💻 Option 3 — VMware Workstation / Player

- **Workstation Pro**: use Snapshots → Restore `baseline`.  
- **Player**: remove the broken VM and re-open/import the OVA.  
- Verify the network adapter is Host-only / Internal as the lab requires.

---

## 🔍 How to discover each VM’s IP (DHCP-friendly methods)

After you start the restored VMs, use these discovery steps to find their IP addresses — do **not** assume static IPs.

### A — From the Kali attacker VM (recommended)
1. Find your attacker interface & subnet:
```bash
ip -4 addr show
# look for the host-only / lab interface (e.g., vboxnet0 / eth0) and note the CIDR (e.g., 192.168.56.0/24)
```
2. Do a quick ping-sweep to list active hosts on that subnet:
```bash
# replace <subnet> with the CIDR you found, e.g. 192.168.56.0/24
nmap -sn <subnet>
```
3. Check ARP table for MAC ⇄ IP mapping:
```bash
arp -n
```
4. Optionally use `arp-scan` or `nbtscan` if available:
```bash
sudo arp-scan --localnet
nbtscan <subnet>
```

### B — From the host (VirtualBox-specific)
List host-only networks and check DHCP range:
```bash
VBoxManage list hostonlyifs
# On Linux/macOS you can also:
ip -4 addr show vboxnet0
# Use the shown network to run a ping-scan or nmap from the host
```
You can also query guest IP (requires Guest Additions):
```bash
VBoxManage guestproperty get "VM Name" "/VirtualBox/GuestInfo/Net/0/V4/IP"
```

### C — From the Windows VM (inside the VM)
If you can open the VM console in the hypervisor:
```powershell
ipconfig
```
Note the IPv4 address and subnet mask; use it to compute the scan subnet from Kali.

### D — Name-based discovery (if lab uses mDNS/hostnames)
- Use `avahi-browse -a` (Linux) or `dns-sd` on macOS to find `.local` hostnames.  
- Use `nbtscan` or `smbclient -L //<ip>` to identify Windows SMB hosts.

---

## ✅ Post-restore verification (DHCP-safe)

Once you’ve discovered the VM IPs, verify services **by discovery** (examples):

### From Kali (attacker)
```bash
# discover active hosts (replace <subnet>)
nmap -sn <subnet>

# quick service probes on discovered host(s)
nmap -Pn -p 22,80,139,445,3389 <discovered-ip>

# check SSH / HTTP / RDP reachability
ssh user@<discovered-ip>        # Linux target
curl -I http://<discovered-ip>  # web service
xfreerdp /v:<discovered-ip> /u:Administrator  # RDP (Windows)
```

### From the host/hypervisor console (Windows)
```powershell
# verify identity and services
whoami
systeminfo
Get-Service -Name *Rdp*, *SMB*, *TermService*
sc qc VulnerableTest   # if lab includes test service
```

### From the Linux VM console
```bash
whoami
ip -4 addr show
curl -I http://localhost
systemctl status apache2  # or relevant service
```

---

## ⚙️ Troubleshooting (DHCP-focused)

- **Can't find hosts on the network?**  
  - Confirm VM network adapter is *Host-only/Internal* and attached to the same host-only network on each VM.  
  - Check the host-only DHCP server (VirtualBox: *File → Host Network Manager*). Enable DHCP or set a known range.

- **IPs look wrong / no DHCP lease:**  
  - Restart networking inside guest:
    ```bash
    # Linux guest
    sudo dhclient -v <interface>

    # Windows guest (elevated PowerShell)
    ipconfig /renew
    ```
- **Duplicate IPs or MAC conflicts:**  
  - Ensure you removed old duplicate VMs before importing new OVA; change MAC in VM settings if necessary.

- **Guest Additions not reporting IP:**  
  - Guest Additions / VMware Tools must be installed for host to query guest IP metadata. If unavailable, use network discovery (nmap/arp) instead.

---

## 🧩 Best practices & instructor notes
- Use snapshots named consistently: `baseline-windows`, `baseline-linux`, `baseline-kali`.  
- Keep an OVA archive in the course appendix for full re-import.  
- After restore, run the discovery workflow above and *record* each VM’s found IP + hostname in your lab notes.  
- Do **not** publish or commit discovered IPs or instructor-only credentials to the repo.

---

## ✅ Summary
- Always restore via snapshot where possible.  
- If snapshots are unavailable, re-import the OVA.  
- Use DHCP-aware discovery (nmap, arp, ipconfig/ip addr, VBoxManage guestproperty) to find each VM’s IP — do **not** rely on static addresses.  
- Verify services after discovery and document evidence before starting the lab.

