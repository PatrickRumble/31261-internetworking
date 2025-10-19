# Interactive Cybersecurity Lab Environment

This project provides an **interactive, virtualised environment** designed to help students gain hands-on experience with common cybersecurity vulnerabilities and attack techniques.  
It is based on the **OWASP Top 10** framework and emphasises safe, isolated practice.

The environment consists of three interconnected virtual machines:
- **Linux Target VM** – configured with deliberate vulnerabilities such as SQL injection and file upload flaws.
- **Windows Target VM** – demonstrates weaknesses like insecure authentication and poor configuration.
- **Kali Attacker VM** – preloaded with penetration testing tools to simulate how attackers exploit vulnerabilities. This machine will be used **throughout all labs**.

## 🎯 Learning Objectives
- Understand how common vulnerabilities appear in real-world systems.
- Practice identifying, exploiting, and remediating security flaws.
- Gain familiarity with widely used penetration testing tools.
- Develop a defensive mindset by learning mitigation strategies.

## 📂 Repository Structure
```
31261-internetworking/
├── README.md             # Project overview (this file)
├── prerequisites.md      # Setup instructions and requirements
├── docs/
│   ├── reset.md
│   └── future-plans.md
├── labs/
│   ├── 00-introduction/
│   │   └── overview.md
│   ├── 01-linux-vm/
│   │   ├── overview.md
│   │   ├── vulnerabilities.md
│   │   └── instructions.md
│   ├── 02-windows-vm/
│   │   ├── overview.md
│   │   ├── vulnerabilities.md
│   │   └── instructions.md
│   └── 03-kali-attacker/
│       ├── overview.md
│       └── tools.md
```

## 🚀 How to Use
1. Begin with `prerequisites.md` to set up the environment.  
2. Read the **00-introduction** lab for project context.  
3. Work through **Linux (01)** and **Windows (02)** labs in order.  
   - You will use the **Kali Attacker VM** during these labs to perform exploits.  
4. Use the **03-Kali-Attacker** lab as a dedicated reference for learning penetration testing tools.  
   - Refer back to it whenever a Linux/Windows lab mentions a tool.

## ⚠️ Disclaimer
This project is for **educational purposes only**.  
All vulnerabilities exist only within the lab environment.  
Do not attempt to use these techniques outside of this controlled setting.
