# 🔐 Security Policy

**maatify/mongo-activity**

---

## 🛡 Supported Versions

The following versions of **maatify/mongo-activity** receive security updates:

| Version | Status            |
|---------|-------------------|
| **1.x** | ✅ Fully supported |
| < 1.0.0 | ❌ Not supported   |

If a security issue affects older versions, updating to the latest release is required.

---

## 📣 Reporting a Vulnerability

If you discover a security vulnerability, **please DO NOT** open a public GitHub issue.

Instead, contact the maintainer privately:

### **📧 Email**

**[security@maatify.dev](mailto:security@maatify.dev)**

or
**[mohamed@maatify.dev](mailto:mohamed@maatify.dev)**

### **🔒 Required Details**

When reporting, please include:

* A clear description of the vulnerability
* Steps to reproduce
* Expected behavior vs actual behavior
* Potential impact
* Any relevant logs, payloads, or PoC
* A suggested fix (optional)

You will receive a response within **24–48 hours**.

---

## 🤝 Coordinated Disclosure

To protect the ecosystem:

* Do **not** publicly reveal the issue before a fix is released.
* The maintainer will work with you to verify, patch, and release a secure update.
* You may be credited in the release notes unless anonymity is requested.

---

## 🧪 Security Best Practices (For Users)

Using this library safely requires:

### 1️⃣ **Securing MongoDB**

* Disable public access
* Use strong authentication
* Restrict network access to trusted hosts
* Enable TLS where possible

### 2️⃣ **Environment Secrets**

Ensure your `.env` file is **not committed to Git** and includes:

```
MONGO_URI=your-secure-uri
MONGO_DB_ACTIVITY=...
MONGO_DB_ACTIVITY_ARCHIVE=...
```

### 3️⃣ **Activity Data Sensitivity**

Logged activity may include:

* User IDs
* Admin actions
* Sensitive entity references

Make sure your access control restricts who can query the logs.

---

## 🔄 Vulnerability Fix Process

When a vulnerability is confirmed:

1. Issue is replicated and validated privately
2. Patch is developed and tested
3. Security update is tagged and released
4. Advisory is published in GitHub Security Advisories
5. Responsible reporters may be credited

---

## 🧩 Community Expectations

By using this package, you agree to:

* Avoid exploiting discovered vulnerabilities
* Report them responsibly
* Refrain from publicly disclosing without coordination

---

> 🧩 *maatify/mongo-activity— Unified Data Connectivity & Diagnostics Layer*  
> © 2025 Maatify.dev • Maintained by Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))

---
## 🪪 Maintainer

**© 2025 Maatify.dev**  
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:  
https://github.com/Maatify/data-adapters

---

<p align="center">
  <sub><span style="color:#777">Built with ❤️ by <a href="https://www.maatify.dev">Maatify.dev</a> — Unified Ecosystem for Modern PHP Libraries</span></sub>
</p>

