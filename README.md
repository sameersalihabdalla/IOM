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
| 1 | `screenshot/1.jpg` | **Login Screen:** Secure authentication portal for admin and authorized staff. |
| 2 | `screenshot/2.jpg` | **Policies & Debt Management:** Main dashboard table displaying insurance documents, statuses, and WhatsApp integration. |
| 3 | `screenshot/3.jpg` | **Add New Policy:** Form to input insured party details, vehicle category, and total premiums. |
| 4 | `screenshot/4.jpg` | **Success Alert:** Modal confirmation upon successfully saving policy records. |
| 5 | `screenshot/5.jpg` | **Updated Policy List:** Reflecting newly added policies in real-time. |
| 6 | `screenshot/6.jpg` | **Business Analytics Dashboard:** Overview cards showing revenue, total documents, and growth percentages. |
| 7 | `screenshot/7.jpg` | **Advanced Charts:** Visual breakdowns of top insurance categories, weekly performance, and debt statistics. |
| 8 | `screenshot/8.jpg` | **Third-Party Report Sheet:** Comprehensive printable financial summary and period filtering. |
| 9 | `screenshot/9.jpg` | **Daily Report (`report_today.php`):** Detailed breakdown of daily policies, installments, and commissions. |
| 10 | `screenshot/10.jpg` | **Client Management (`clients.php`):** Registering and managing customer profiles and phone contacts. |
| 11 | `screenshot/11.jpg` | **Insurance Categories Setup (`cat.php`):** Managing policy types, vehicle categories, office & agent commissions. |
| 12 | `screenshot/12.jpg` | **Category Editor View:** Dedicated interface for adding/editing insurance tiers and pricing. |
| 13 | `screenshot/13.jpg` | **Account Statement & SMS (`send_sms.php`):** Generating account summaries and sending automated billing details. |

## 🚀 Installation & Setup
1. Clone or download this repository.
2. Place the `IOM` folder in your local web server root directory (e.g., `C:/xampp/htdocs/IOM/`).
3. Start **Apache** and **MySQL** from XAMPP Control Panel.
4. Create a database named `iom_db` via phpMyAdmin and import any provided SQL schema.
5. Access the system in your browser:
   ```text
   http://localhost/IOM/
   ```

---

<a name="-التوثيق-باللغة-العربية"></a>
# 🇸🇦 التوثيق باللغة العربية

## 📋 جدول المحتويات
- [نبذة عن المشروع](#نبذة-عن-المشروع)
- [المميزات الرئيسية](#المميزات-الرئيسية-1)
- [التقنيات المستخدمة](#التقنيات-المستخدمة-1)
- [لقطات الشاشة (Screenshots)](#لقطات-الشاشة-screenshots-1)
- [طريقة التثبيت والتشغيل](#طريقة-التثبيت-والتشغيل-1)
- [الترخيص](#الترخيص-1)

---

## 🚗 نبذة عن المشروع
**IOM** هو نظام برمجي متكامل لإدارة مكاتب تأمين السيارات (تأمين الطرف الثالث)، مصمم خصيصاً لتنظيم العمليات اليومية، إدارة وثائق التأمين، متابعة الديون والمديونيات، حسابات العُملاء، إدارة العمولات الفئوية، واستخراج التقارير المالية والإحصائية بدقة عالية.

## ✨ المميزات الرئيسية
1. **إدارة وثائق التأمين:** إصدار ومتابعة وثائق الطرف الثالث لمختلف فئات المركبات (ركشة، لوري، دفار، أمجاد، باصات، وغيرها).
2. **نظام متابعة المديونيات:** تتبع المبالغ المتبقية على العملاء والمكاتب مع إشعارات وتكامل مع واتساب.
3. **لوحة المؤشرات التحليلية (Dashboard):** رسوم بيانية ومؤشرات أداء شاملة (نسب النمو، الفئات الأكثر طلباً، والأداء اليومي/الأسبوعي).
4. **إدارة العُملاء (`clients.php`):** تسجيل وتعديل ومتابعة حالات العملاء وأرقام الهواتف.
5. **إدارة أنواع التأمين والفئات (`cat.php`):** تحكم كامل بأنواع التأمين، تكاليف الإصدار، أقساط الركاب، عمولات المكتب، وعمولات الوكلاء.
6. **كشف الحساب والرسائل (`send_sms.php`):** إصدار كشافات حساب مختصرة وإرسال الفواتير وإجماليات الخدمات والطباعة.

## 🛠️ التقنيات المستخدمة
- **الخلفية (Backend):** PHP (Native / PDO)
- **قاعدة البيانات (Database):** MySQL / MariaDB
- **الواجهات الأمامية (Frontend):** HTML5, CSS3, JavaScript, Bootstrap
- **الخادم المحلي:** Apache (عبر XAMPP أو WampServer)

## 📸 لقطات الشاشة (Screenshots)

| الرقم | واجهة النظام | الوصف |
|:---:|---|---|
| 1 | `screenshot/1.jpg` | **شاشة تسجيل الدخول:** واجهة المصادقة الآمنة لدخول النظام. |
| 2 | `screenshot/2.jpg` | **إدارة الوثائق والديون:** الجدول الرئيسي لعرض وثائق التأمين وحالات السداد ومتابعة المديونيات. |
| 3 | `screenshot/3.jpg` | **إضافة وثيقة جديدة:** إدخال بيانات المُؤمّن له، الفئة، والمبلغ الإجمالي. |
| 4 | `screenshot/4.jpg` | **رسالة التأكيد:** نافذة منبثقة تؤكد نجاح عملية الحفظ. |
| 5 | `screenshot/5.jpg` | **قائمة الوثائق المحدثة:** ظهور الوثيقة الجديدة بشكل فوري في النظام. |
| 6 | `screenshot/6.jpg` | **لوحة متابعة الأعمال:** المؤشرات العامة، إجمالي المبالغ، ونسب النمو. |
| 7 | `screenshot/7.jpg` | **الرسوم البيانية التحليلية:** توزيع الفئات الأكثر طلباً، الأداء اليومي والأسبوعي، وتوزيع الوثائق. |
| 8 | `screenshot/8.jpg` | **كشف وثائق الطرف الثالث:** تقرير تفصيلي لفترة محددة مع خيارات الطباعة. |
| 9 | `screenshot/9.jpg` | **تقرير وثائق اليوم (`report_today.php`):** أرقام الوثائق، الأقساط، والعمولات اليومية. |
| 10 | `screenshot/10.jpg` | **إدارة العملاء (`clients.php`):** إضافة وتعديل بيانات العملاء وأرقام الهواتف والحالة. |
| 11 | `screenshot/11.jpg` | **إدارة أنواع التأمين والفئات (`cat.php`):** إدارة الفئات، أقساط الركاب، التكلفة، وعمولات المكتب والوكيل. |
| 12 | `screenshot/12.jpg` | **نموذج إدخال الفئات:** واجهة إضافة وتعديل أسعار وتكاليف الفئات التأمينية. |
| 13 | `screenshot/13.jpg` | **كشف الحساب والرسائل (`send_sms.php`):** تفاصيل الحسابات، طباعة الفواتير، وإجماليات الطباعة والخدمات الأخرى. |

## 🚀 طريقة التثبيت والتشغيل
1. قُم بتحميل المشروع ونقله إلى مجلد الخادم المحلي (مثلاً `C:/xampp/htdocs/IOM/`).
2. شغّل خدمات **Apache** و **MySQL** عبر لوحة تحكم XAMPP.
3. أنشئ قاعدة بيانات جديدة (`iom_db`) في `phpMyAdmin` واستيراد ملف الجداول.
4. افتح المتصفح على الرابط:
   ```text
   http://localhost/IOM/
   ```

## 📄 الترخيص
هذا المشروع مرخص تحت رخصة **MIT**.
