# Interactive Cybersecurity Lab Environment

This project provides an **interactive, virtualised environment** designed to help students gain hands-on experience with common cybersecurity vulnerabilities and attack techniques.  
It is based on the **OWASP Top 10** framework and emphasises safe, isolated practice.

The environment consists of three interconnected virtual machines:
- **Linux Target VM** – configured with deliberate vulnerabilities such as SQL injection and file upload flaws.
- **Windows Target VM** – demonstrates weaknesses like insecure authentication and poor configuration.
- **Kali Attacker VM** – preloaded with penetration testing tools to simulate how attackers exploit vulnerabilities.

## 🎯 Learning Objectives
- Understand how common vulnerabilities appear in real-world systems.
- Practice identifying, exploiting, and remediating security flaws.
- Gain familiarity with widely used penetration testing tools.
- Develop a defensive mindset by learning mitigation strategies.

## 📂 Repository Structure
31261-internetworking/  
├── README.md # Project overview (this file)  
├── prerequisites.md # Setup instructions and requirements  
├── docs/ # Project-wide documentation  
│ ├── reset.md  
│ ├── future-plans.md  
│ └── references.md  
├── labs/  
│ ├── 00-introduction/  
│ │ └── overview.md  
│ ├── 01-linux-vm/  
│ │ ├── overview.md  
│ │ ├── vulnerabilities.md  
│ │ └── instructions.md  
│ ├── 02-windows-vm/  
│ │ ├── overview.md  
│ │ ├── vulnerabilities.md  
│ │ └── instructions.md  
│ └── 03-kali-attacker/  
│   ├── overview.md  
│   ├── tools.md  
│   └── instructions.md  

## 🚀 How to Use
1. Start with [`prerequisites.md`](./prerequisites.md) to set up your environment.
2. Work through labs in order:
   - `00-introduction` – project context and usage guide  
   - `01-linux-vm` – misconfigurations and server-side vulnerabilities  
   - `02-windows-vm` – insecure user/application practices  
   - `03-kali-attacker` – attacker’s perspective and penetration tools  
3. Each lab includes:
   - `overview.md` – purpose and context  
   - `vulnerabilities.md / tools.md` – theoretical background on vulnerabilities and tools  
   - `instructions.md` – exploitation, solutions, and remediation  

## ⚠️ Disclaimer
This project is for **educational purposes only**.  
All vulnerabilities exist **only within the lab environment**. Do not attempt to use these techniques outside of this controlled setting.
