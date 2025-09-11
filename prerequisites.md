# Prerequisites

Before starting the labs, ensure you have the following installed and configured.

## 🖥️ System Requirements
- Host machine with **at least 16 GB RAM** (recommended: 8 GB minimum)
- At least **80 GB free disk space**
- A processor with **virtualization support** (Intel VT-x or AMD-V)
- Operating System: Linux, macOS, or Windows 10/11

## 🔧 Required Software
- **VirtualBox** or **VMware Workstation Player**
- **VirtualBox Extension Pack** (if using VirtualBox)
- (Optional) **Vagrant** for automated provisioning

## 📦 Virtual Machines
The lab environment includes three VMs:
1. **Linux VM** (target) – runs vulnerable services
2. **Windows VM** (target) – runs insecure applications
3. **Kali Linux VM** (attacker) – preloaded with penetration testing tools

VM images and configuration files will be provided by the instructors.  

## 🌐 Network Configuration
- All VMs must be connected to an **internal host-only network**.  
- Example configuration:
  - Linux VM → `192.168.56.101`
  - Windows VM → `192.168.56.102`
  - Kali VM → `192.168.56.103`

This ensures the environment is **isolated** from the public internet.

## 🚀 Getting Started
1. Import the VM images into VirtualBox/VMware.
2. Assign the network interfaces according to the configuration above.
3. Verify connectivity:
   ```bash
   # From Kali, ping each VM
   ping 192.168.56.101   # Linux VM
   ping 192.168.56.102   # Windows VM

Once all VMs are connected, begin with labs/00-Introduction/overview.md
