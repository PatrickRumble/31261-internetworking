# Future Work & Extension Roadmap

This document summarises realistic and high-value extensions we can pursue after the core semester deliverable. The items are prioritised by educational impact and implementation complexity, and each entry includes a short rationale, main tasks, dependencies, and a suggested success criterion.

> Note: the current project deliverable focuses on a locally deployable three-VM testbed (Linux target, Windows target, Kali attacker) with guided exercises, remediation notes and snapshot/reset support. The items below outline how to expand functionality, assessment capability, and pedagogical depth in future iterations.

---

## 1. High-priority extensions (highest educational value)

### 1.1 Automated verification & lightweight grading scripts
**Why:** Reduces instructor overhead and gives students immediate feedback on whether they completed remediation tasks correctly.  
**Main tasks:**
- Create small, idempotent verification scripts (one per lab) that check for expected configuration changes (service status, file permissions, patched code, disabled endpoints, registry keys, etc.).
- Integrate scripts into the Kali attacker as `tools/verify_<lab>.sh` (or PowerShell for Windows checks).
- Add an instructor script to aggregate student results into a CSV log.
**Dependencies:** Access to VM internals (SSH/WinRM), baseline snapshots, simple JSON/YAML test definitions.  
**Success criterion:** Each lab has at least one verification script that returns a clear PASS/FAIL with a short remediation hint.

### 1.2 Improve remediation guidance and visual aids
**Why:** Usability testing showed students benefit from more screenshots and shorter, clearer remediation steps.  
**Main tasks:**
- Expand `instructions.md` remediation sections with annotated screenshots and short command checklists.
- Create a single-page “cheat sheet” per lab summarising the fix steps and common pitfalls.
**Dependencies:** Current labs completed and screenshots captured.  
**Success criterion:** Reduced average time-to-remediate in pilot tests and improved user confidence scores.

---

## 2. Medium-priority extensions (adds depth / realism)

### 2.1 Basic defensive monitoring (log collection & simple SIEM)
**Why:** Connects offensive actions to defensive detection — improves students’ ability to practise detection and response.  
**Main tasks:**
- Add a lightweight ELK/EFK stack or a preconfigured filebeat/Winlogbeat forwarding to a central collector running on a small VM (or run a local instance on Kali).
- Create exercises showing how an attack appears in logs and how to construct simple detection rules.
**Dependencies:** Extra VM resources or a lightweight docker approach; disk and RAM considerations.  
**Success criterion:** Students can reproduce an attack and find corresponding log entries with a provided query.

### 2.2 Enhanced Windows scenarios (small domain / AD basics)
**Why:** Many enterprise attacks use Active Directory; a scaled-down AD lab adds high educational value.  
**Main tasks:**
- Create an optional lab with a single-domain controller and one workstation (keeps complexity manageable).
- Seed with common AD misconfigurations (exposed SMB shares, weak delegation, Kerberoastable SPNs).
**Dependencies:** Additional Windows Server image, licensing considerations for lab use, more host resources.  
**Success criterion:** Students complete at least one AD-style lateral movement exercise and perform a documented remediation.

---

## 3. Lower-priority / aspirational features

### 3.1 Cloud-deployable variant
**Why:** Makes the lab accessible to remote cohorts and simplifies distribution at scale.  
**Main tasks:**
- Create Terraform/CloudFormation templates to deploy the environment in a controlled cloud project (secure VPC, isolated subnet).
- Implement cost controls and a snapshot/rollback mechanism.
**Dependencies:** Cloud credits, institutional approval, careful security review.  
**Success criterion:** A working cloud template that instructors can deploy in a sandbox account with minimal configuration.

### 3.2 Gamification & achievement tracking
**Why:** Increases student motivation and supports formative assessment.  
**Main tasks:**
- Add a simple scoring system or badges for completed tasks (could be offline: update a `progress.json` file per VM).
- Optionally build a lightweight dashboard to view progress (hosted on Kali or as a static site).
**Dependencies:** Verification scripts and a method for safe state reporting.  
**Success criterion:** Students can claim a badge or score after verification scripts report PASS.

### 3.3 Automated environment provisioning (infrastructure as code)
**Why:** Improves reproducibility and speeds environment setup for instructors.  
**Main tasks:**
- Create scripts to build the VMs and apply lab configuration automatically (Vagrant, Packer, or Ansible playbooks).
**Dependencies:** Testing across host OS types and virtualiser versions.  
**Success criterion:** One-command reprovisioning on a clean host that produces identical baseline VMs.

---

## 4. Research, evaluation & pedagogy work

- **Formal usability studies:** design pre/post-tests to measure learning gains (knowledge checks, confidence surveys).
- **Curriculum mapping:** map each lab objective to course-level outcomes and possible assessment rubrics.
- **Accessibility review:** ensure materials are usable for students with assistive requirements (alt-text for screenshots, keyboard-first instructions).

---

## 5. Risks and mitigations

- **Resource constraints:** Some extensions (cloud variant, AD lab, SIEM) require extra CPU/RAM or cloud credits. *Mitigation:* provide lightweight alternatives or optional add-ons.
- **Licensing & legality:** Using certain Windows Server features or third-party tools may require licenses. *Mitigation:* use trial/educational images and clearly document licensing needs.
- **Security exposure:** Cloud-hosted or poorly isolated instances could be abused. *Mitigation:* network isolation, strict ingress/egress controls, and institutional approval prior to public deployment.

---

## 6. Suggested next steps (practical, immediate)
1. Implement verification scripts for the top 3 labs (SQLi, file upload, unquoted service path).  
2. Improve remediation docs for labs that user feedback flagged as confusing (Print Spooler, NTLM/SMB concepts).  
3. Add 2–3 annotated screenshots per remediation guide to speed student comprehension.  
4. Create a single `deploy/` folder containing one or two provisioning scripts (Vagrant or simple bash) to simplify replication for instructors.

---

## 7. Acceptance criteria (how to know a feature is “done”)
For each extension, define “done” as:
- Code/scripts committed to the repository with clear README and usage steps.  
- Small, reproducible test (verification script) demonstrating correct behaviour.  
- Documentation (overview, instructions, remediation) updated and reviewed by at least one peer.  
- If resource-intensive (SIEM, AD, cloud), a short cost/security note is included with deployment instructions.

---

*This roadmap is intended to be practical and modular: features can be introduced incrementally, tested with students, and adapted based on feedback and resource availability.*
