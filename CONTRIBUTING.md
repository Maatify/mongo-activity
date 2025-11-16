# 🤝 Contributing to maatify/mongo-activity

Thank you for considering contributing to **maatify/mongo-activity**!
We welcome improvements, bug fixes, tests, documentation, and new ideas that help strengthen the Maatify Ecosystem.

---

## 🧭 Contribution Guidelines

### ✔ Requirements

Before contributing, ensure you have:

* PHP **8.4+**
* Composer **v2**
* MongoDB **2.x driver (`mongodb/mongodb`)**
* Local MongoDB instance (or remote URI)
* PHPUnit for running tests

---

## 🔀 How to Contribute

### 1️⃣ **Fork the Repository**

Click **Fork** on GitHub and clone your fork:

```bash
git clone https://github.com/<your-username>/mongo-activity.git
cd mongo-activity
```

---

### 2️⃣ **Create a Feature Branch**

Use meaningful branch names:

```bash
git checkout -b feature/add-archive-optimization
```

Examples:

* `fix/search-pagination-edge-case`
* `feature/add-new-activity-type`
* `docs/improve-readme`

---

### 3️⃣ **Install Dependencies**

```bash
composer install
```

Copy environment:

```bash
cp .env.example .env
```

---

### 4️⃣ **Follow Coding Standards**

This project follows:

* **PSR-12**
* Strict typing (`declare(strict_types=1);`)
* Enums and DTO best practices
* Clean architecture principles

Please format your code before submitting:

```bash
composer fix
```

(if project contains a `fix` script — otherwise use PHP-CS-Fixer manually)

---

### 5️⃣ **Write and Run Tests**

Before submitting a PR, ensure all tests pass:

```bash
vendor/bin/phpunit
```

If adding new features, include new test cases under `/tests`.

---

### 6️⃣ **Commit Messages Style**

Use the standard Maatify commit style:

* `feat:` — new feature
* `fix:` — bug fix
* `refactor:` — internal change
* `test:` — test-only updates
* `docs:` — documentation
* `chore:` — maintenance

Examples:

```
feat: add quarterly archive resolver
fix: correct UTCDateTime conversion for search filters
docs: improve installation instructions in README
```

---

### 7️⃣ **Submit a Pull Request**

Push your branch:

```bash
git push origin feature/add-archive-optimization
```

Then open a PR on GitHub:

* Clearly describe the change
* Link any related issues
* Be concise and technical

We review PRs as quickly as possible.

---

## 🛡 Security Issues

⚠️ **Do NOT open an issue for security vulnerabilities.**
Follow the instructions in `SECURITY.md` to report privately.

---

## 🧱 Code of Conduct

By contributing, you agree to:

* Write clean, tested, maintainable code
* Respect others in discussions and PR reviews
* Avoid breaking backward compatibility without reason
* Use responsible disclosure for vulnerabilities

---

## 🧪 Test Coverage Expectations

Each new feature or bug fix should:

* Have at least one corresponding test
* Not reduce overall test coverage
* Maintain stability of existing behaviors

---

## 🧩 Project Structure Overview

If you modify any of the following directories, make sure logic remains modular:

* `src/Repository` → DB logic
* `src/Enum` → activity types, roles, modules
* `src/Contract` → interfaces
* `src/DTO` → structured data objects
* `scripts/` → automation & CRON tools

---
## 🪪 Maintainer

**© 2025 Maatify.dev**  
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:  
https://github.com/Maatify/data-adapters

---

