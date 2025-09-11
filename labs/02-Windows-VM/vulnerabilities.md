# Windows VM Vulnerabilities

This document outlines the deliberate vulnerabilities in the Windows target VM. Each vulnerability is framed with educational purpose, real-world examples, and mitigation strategies.

---

## Insecure Authentication & Weak Passwords
**Description:** Accounts with weak or default passwords are easy targets for attackers.  
**Educational Purpose:** Students will attempt to exploit weak credentials, demonstrating the importance of strong authentication.  
**Real-World Relevance:** Many real-world breaches occur due to compromised credentials. For example, ransomware campaigns often start by exploiting weak passwords on network shares or administrative accounts. Understanding this helps students appreciate why multi-factor authentication (MFA) and password complexity are critical.

---

## Insecure Web Application Configuration
**Description:** Misconfigured web applications can expose sensitive data or enable unauthorized actions.  
**Educational Purpose:** Students will review IIS and other services to identify insecure defaults and configuration errors.  
**Real-World Relevance:** Misconfiguration is among the top causes of breaches. Leaked directory listings, exposed administrative panels, or overly permissive file permissions have been the root cause of attacks on businesses ranging from e-commerce to government services.

---

## Insufficient Logging and Monitoring
**Description:** Systems without proper logging or monitoring fail to detect attacks.  
**Educational Purpose:** Students will see how lack of logs can obscure attack activity and learn the importance of monitoring systems for security events.  
**Real-World Relevance:** Many compromises go unnoticed for months due to insufficient monitoring. Attackers exploit this blind spot to steal data, deploy ransomware, or maintain persistent access. Logging and monitoring are the first line of defense for incident response.

---

## Key Takeaways
- Always enforce strong authentication policies.
- Regularly audit and harden web applications.
- Implement logging and continuous monitoring to detect malicious activity early.
