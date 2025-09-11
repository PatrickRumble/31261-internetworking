# Linux VM Vulnerabilities

This document highlights the deliberate vulnerabilities included in the Linux target VM. Each vulnerability is presented with a detailed explanation, real-world relevance, and educational purpose.

---

## SQL Injection
**Description:** SQL injection occurs when user input is improperly sanitized, allowing attackers to manipulate backend database queries.  
**Educational Purpose:** Students will exploit SQL injection to retrieve sensitive information from the database and understand how unsanitized input can be dangerous.  
**Real-World Relevance:** SQL injection remains one of the most common web vulnerabilities. High-profile breaches, including credit card and personal information leaks, often result from SQL injection attacks. Understanding this vulnerability teaches students the importance of parameterized queries, input validation, and secure coding practices.

**Example:** Attackers may enter malicious SQL statements in a login form to bypass authentication or extract sensitive data.  

---

## Brute Force Attack
**Description:** Brute force attacks systematically attempt all possible password combinations to gain unauthorized access.  
**Educational Purpose:** Students will attempt login on weak accounts, gaining insight into why strong, unique passwords are critical.  
**Real-World Relevance:** Weak passwords or default credentials are often the first point of exploitation in server breaches. Organizations such as healthcare providers and small businesses have suffered ransomware or data theft because attackers successfully guessed weak passwords.

**Example:** Using automated tools, an attacker could compromise SSH access by trying thousands of passwords until one succeeds.

---

## File Upload Vulnerability
**Description:** Unrestricted file uploads allow attackers to place malicious files on the server.  
**Educational Purpose:** Students will explore file upload mechanisms and observe how improper validation can lead to remote code execution.  
**Real-World Relevance:** Many content management systems or web applications have suffered from malware installation, website defacement, or full server compromise due to unvalidated file uploads.

**Example:** Uploading a web shell that allows an attacker to execute commands remotely, gain administrative privileges, or pivot to other systems on the network.

---

## Key Takeaways
- Always validate and sanitize user inputs.
- Employ strong authentication and password policies.
- Restrict file upload types and implement access controls.
- Learn to think like an attacker to design better defenses.
