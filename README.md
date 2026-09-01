# 🚗 IOM - Car Insurance Office Management System
### نظام إدارة مكاتب تأمين السيارات (IOM)

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B%20%2F%208.x-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/Database-MySQL-orange.svg)](https://www.mysql.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

---

## 🌐 Languages / اللغات
- [English Documentation](#-english-documentation)
- [التوثيق باللغة العربية](#-التوثيق-باللغة-العربية)

---

<a name="-english-documentation"></a>
# 🇬🇧 English Documentation

## 📋 Table of Contents
- [About the Project](#about-the-project)
- [Key Features](#key-features)
- [Tech Stack](#tech-stack)
- [Screenshots & UI Walkthrough](#screenshots--ui-walkthrough)
- [Installation & Setup](#installation--setup)
- [License](#license)

## 🚗 About the Project
**IOM** is a comprehensive, robust web-based management system designed specifically for car insurance offices (Third-Party Insurance). It streamlines daily operations, policy issuing, debt tracking, customer accounts, commission management, and real-time financial reporting.

## ✨ Key Features
1. **Policy & Insurance Management:** Issue and track third-party insurance policies across various vehicle categories with automated premium calculations.
2. **Debt & Liabilities Tracking:** Monitor client dues and office debts with real-time balance aggregations and alerts.
3. **Analytics Dashboard:** Visual charts and KPI metrics displaying top insurance categories, daily/weekly performance, and revenue growth.
4. **Customer & Client Management:** Easily register, update, and manage customer records with local phone number validation and status toggling (Active/Inactive).
5. **Insurance Categories & Commission Control (`cat.php`):** Dynamic management of policy types, office commissions, agent commissions, and issuance fees.
6. **Account Statements & Messaging (`send_sms.php`):** Generate short account statements and send invoices/notifications.

## 🛠️ Tech Stack
- **Backend:** Native PHP (PDO / MySQLi)
- **Database:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3, JavaScript, Responsive UI Frameworks
- **Server:** Apache / Nginx (XAMPP / WampServer)

## 📸 Screenshots & UI Walkthrough

| # | Interface / Screen | Description |
|---|---|---|
| 1 | `screenshot/1.png` | **Login Screen:** Secure authentication portal for admin and authorized staff. |
| 2 | `screenshot/2.png` | **Policies & Debt Management:** Main dashboard table displaying insurance documents, statuses, and WhatsApp integration. |
| 3 | `screenshot/3.png` | **Add New Policy:** Form to input insured party details, vehicle category, and total premiums. |
| 4 | `screenshot/4.png` | **Success Alert:** Modal confirmation upon successfully saving policy records. |
| 5 | `screenshot/5.png` | **Updated Policy List:** Reflecting newly added policies in real-time. |
| 6 | `screenshot/6.png` | **Business Analytics Dashboard:** Overview cards showing revenue, total documents, and growth percentages. |
| 7 | `screenshot/7.png` | **Advanced Charts:** Visual breakdowns of top insurance categories, weekly performance, and debt statistics. |
| 8 | `screenshot/8.png` | **Third-Party Report Sheet:** Comprehensive printable financial summary and period filtering. |
| 9 | `screenshot/9.png` | **Daily Report (`report_today.php`):** Detailed breakdown of daily policies, installments, and commissions. |
| 10 | `screenshot/10.png` | **Client Management (`clients.php`):** Registering and managing customer profiles and phone contacts. |
| 11 | `screenshot/11.png` | **Insurance Categories Setup (`cat.php`):** Managing policy types, vehicle categories, office & agent commissions. |
| 12 | `screenshot/12.png` | **Category Editor View:** Dedicated interface for adding/editing insurance tiers and pricing. |
| 13 | `screenshot/13.png` | **Account Statement & SMS (`send_sms.php`):** Generating account summaries and sending automated billing details. |

## 🚀 Installation & Setup
1. Clone or download this repository.
2. Place the `IOM` folder in your local web server root directory (e.g., `C:/xampp/htdocs/IOM/`).
3. Start **Apache** and **MySQL** from XAMPP Control Panel.
4. Create a database named `iom_db` via phpMyAdmin and import any provided SQL schema.
5. Access the system in your browser:
   ```text
   http://localhost/IOM/
