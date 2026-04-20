# Eka Cloudflare DNS Manager

> 🚀 Production-ready Cloudflare DNS management system built with pure PHP 8+ and zero dependencies.

Eka Cloudflare DNS Manager is a high-performance, modular, and secure web-based management panel developed from scratch without using any external frameworks such as Laravel or Symfony. Built on a clean MVC (Model-View-Controller) architecture, it enables fast and reliable management of Cloudflare DNS operations including zones, records, bulk actions, and missing record scanning.

---

## 🚀 Features

* ⚡ Advanced MVC Architecture (Router, Controller, Model fully custom)
* 🌐 Multi Cloudflare Account & Token Management
* 🧠 Full DNS Control (A, AAAA, CNAME, TXT, MX)
* 🔁 Bulk DNS Operations (mass record creation & updates)
* 🔍 Missing DNS Record Scanner (auto-detect & auto-create)
* 🧾 Smart Duplicate Detection (skips existing records safely)
* 📊 Zone & Domain Management Dashboard
* 📝 Logging System (/storage/logs/app.log)
* 🎨 Clean Bootstrap 5 Admin Interface
* 🔐 Secure Token Storage (masked & protected)
* 🔗 Dynamic Base URL (works on any domain automatically)

---

## 📦 Requirements

* PHP 8.0 or higher
* MySQL 5.7+ or MariaDB
* cURL Extension (for Cloudflare API)
* PDO Extension (for database)
* Web Server (Apache/Nginx with mod_rewrite enabled)

---

## ⚙️ Installation

1. Upload project files to your root directory (no /public folder required)
2. Import database files:

```
database/eka_cloudflare
```

3. Configure database settings:

```
config/database.php
```

4. Configure application settings:

```
config/app.php
```

5. Access the system via your domain:

```
http://your-domain.com
```

---

## 🔐 Default Admin Access

Email: [admin@admin.com](mailto:admin@admin.com)
Password: 123456

---

## ⚡ Core Capabilities

### 🌐 Cloudflare Integration

* Token-based authentication
* Multi-account management
* Real-time zone fetching

### 🧩 DNS Management

* Create, update, delete DNS records
* Support for A, AAAA, CNAME, TXT, MX
* Automatic duplicate detection

### 🔁 Bulk Operations

* Apply same IP to multiple subdomains
* Mass DNS updates
* Import-based operations

### 🔍 Missing Record Scanner

* Detect missing records for root, www and custom subdomains
* Generate detailed report
* One-click auto creation of missing records

### 🧾 Logging System

* Logs all API responses and system errors
* Accessible via admin panel

---

## 🔒 Security

* PDO prepared statements for SQL Injection protection
* CSRF protection middleware
* Authentication middleware for secure access
* API tokens stored securely and masked in UI

---

## 🏗️ Architecture

* Pure PHP MVC structure
* Modular and extendable codebase
* No external dependencies
* Production-ready system design

---

## 🛣️ Roadmap

* API access layer for external integrations
* Advanced role & permission system
* Queue system for large DNS operations
* UI improvements and analytics dashboard

---

## ⭐ Why this project?

This system is designed based on real-world hosting and DNS management needs. It is optimized for performance, automation, and scalability, making it ideal for developers and system administrators.

---

## 🤝 Contributing

Pull requests are welcome. The project is actively maintained and continuously improved.

---

## 📄 License

MIT License
