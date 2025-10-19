# Windows Target VM Overview

The Windows target VM is designed to demonstrate common configuration and authentication vulnerabilities present in Windows-based environments. It provides a controlled setting for students to practice identifying, exploiting, and mitigating weaknesses related to remote access, service misconfigurations, and legacy authentication protocols.

## Purpose
This lab focuses on security flaws and misconfigurations frequently found in enterprise Windows systems. Students will gain practical experience in:

- Exploiting insecure remote access services such as RDP.
- Identifying and mitigating privilege escalation flaws (e.g., unquoted service paths).
- Understanding authentication attacks such as NTLM relay.
- Applying defensive measures to harden Windows hosts and services.

## Structure
The Windows VM is pre-configured with:
- A running **Remote Desktop Protocol (RDP)** service for secure and insecure access demonstrations.
- A **deliberately misconfigured service** with an unquoted executable path for privilege escalation exercises.
- Enabled **SMB and NTLM authentication services** to demonstrate credential relay attacks.
- Step-by-step guidance for each lab exercise (detailed in `instructions.md`).

## Real-World Relevance
Windows systems are a primary target in both enterprise and cloud environments. Misconfigured remote services, weak authentication settings, and legacy protocols like NTLM continue to be leveraged by attackers for ransomware deployment, credential theft, and lateral movement. By replicating these real-world scenarios, students develop an understanding of how attackers exploit such weaknesses and how defenders can detect, prevent, and respond effectively.

## Learning Outcomes
By completing the Windows VM lab, students will:
1. Identify and analyse common Windows-specific vulnerabilities and misconfigurations.
2. Understand how insecure remote services and legacy protocols can be exploited.
3. Learn and apply best practices for hardening Windows systems and services.
4. Develop practical experience in detecting, exploiting, and remediating Windows vulnerabilities in a safe, isolated environment.
