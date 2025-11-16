# 🧾 **CHANGELOG — maatify/mongo-activity**

All notable changes to this project will be documented in this file.

---

**Project:** maatify/mongo-activity
**Maintainer:** Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))
**Organization:** [Maatify.dev](https://www.maatify.dev)
**License:** MIT

---

## [1.0.0] — 2025-11-16

### 🎉 Initial Stable Release

#### Added

* Full MongoDB-based activity logging engine.
* Enum-driven activity structure (modules, action types, user roles).
* `ActivityRepository` for logging + searching with pagination.
* `ArchiveRepository` for quarterly archive collections.
* `PeriodResolverRepository` + helper for resolving quarterly periods.
* `ActivityArchiveManager` for automated log archival.
* DTO: `ActivityRecordDTO` for structured inserts.
* Contracts:

    * `AppModuleInterface`
    * `ActivityTypeInterface`
    * `UserRoleInterface`
* Scripts:

    * `mongo-activity-archive.php` (6-month auto-archive)
    * `mongo-activity-ensure-indexes.php` (performance indexes)
* `.env.example` template included.
* Extensive README with installation + DI usage examples.

#### Features

* CRUD/view action tracking.
* Advanced multi-parameter filtering: user, role, module, type, keyword, date range.
* Optimized query indexes for high-speed searching.
* Dual Database Mode: active store + archive store.
* DI-friendly design for Slim and other frameworks.

#### Notes

* Fully compatible with PHP 8.4+.
* Requires `mongodb/mongodb` 2.x driver.
* Designed as a core logging layer for the Maatify Ecosystem.

---

## [Unreleased]

*(Reserved for future updates)*

---

**© 2025 Maatify.dev**  
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:  
https://github.com/Maatify/data-adapters

---

<p align="center">
  <sub><span style="color:#777">Built with ❤️ by <a href="https://www.maatify.dev">Maatify.dev</a> — Unified Ecosystem for Modern PHP Libraries</span></sub>
</p>
