# 🏠 Quetta Home Services Hub

<p align="center">
  <img src="https://img.shields.io/badge/PHP-Core%20PHP-777BB4?style=for-the-badge&logo=php" />
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql" />
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap" />
  <img src="https://img.shields.io/badge/jQuery-JavaScript-0769AD?style=for-the-badge&logo=jquery" />
  <img src="https://img.shields.io/badge/Status-Completed-success?style=for-the-badge" />
</p>

---

## 📌 Project Overview

**Quetta Home Services Hub** is a responsive web-based application developed using **Core PHP**, **MySQL**, **Bootstrap 5**, **HTML5**, **CSS3**, and **jQuery**.

The system allows customers to browse available home services, book services online, and enables administrators to manage services through a secure admin dashboard.

The project is designed with a modern responsive interface while maintaining a simple Core PHP architecture without using any framework.

---

# ✨ Features

### 👨‍💼 Customer Module

- View all available home services
- Responsive service cards
- Service images
- Service descriptions
- Service pricing
- Online booking system
- Mobile-friendly interface

---

### 🔐 Admin Module

- Secure Admin Login
- Session Authentication
- Dashboard
- Add New Services
- Edit Services
- Delete Services
- Upload Service Images
- Logout System

---

### 🎨 User Interface

- Modern Responsive Design
- Bootstrap 5 Layout
- jQuery Effects
- Sticky Navigation Bar
- Professional Footer
- Responsive Cards
- Beautiful Forms
- Font Awesome Icons
- Mobile Friendly

---

# 🛠 Technology Stack

| Technology | Purpose |
|------------|----------|
| HTML5 | Page Structure |
| CSS3 | Styling |
| Bootstrap 5 | Responsive UI |
| JavaScript | Client-side Functionality |
| jQuery | UI Effects |
| PHP (Core) | Backend Development |
| MySQL | Database |
| XAMPP | Local Server |
| VS Code | Code Editor |

---

# 📂 Project Structure

```
Quetta Home Services Hub/
│
├── admin_dashboard.php
├── admin_login.php
├── book.php
├── config.php
├── edit_service.php
├── index.php
├── logout.php
│
├── css/
│   └── style.css
│
├── js/
│   └── script.js
│
├── images/
│
├── uploads/
│
└── README.md
```

---

# 🗄 Database

Database Name

```
quettaserviceshub_db
```

---

## Tables

### admins

| Field |
|------|
| id |
| username |
| password |

---

### services

| Field |
|------|
| id |
| name |
| description |
| price |
| image |

---

### bookings

| Field |
|------|
| id |
| service_id |
| name |
| phone |
| address |
| booking_date |
| created_at |

---

# 🔑 Default Admin Login

| Username | Password |
|----------|----------|
| admin | 1234 |

---

# ⚙ Installation Guide

### 1. Install XAMPP

Start:

- Apache
- MySQL

---

### 2. Copy Project

Copy project folder into

```
xampp/htdocs/
```

---

### 3. Create Database

Open

```
http://localhost/phpmyadmin
```

Create database

```
quettaserviceshub_db
```

Import SQL file or create tables.

---

### 4. Configure Database

Open

```
config.php
```

Update database credentials if needed.

---

### 5. Run Project

Homepage

```
http://localhost/quetta_serviceshub/index.php
```

Admin Login

```
http://localhost/quetta_serviceshub/admin_login.php
```

---

# 📸 Screenshots

Add screenshots after completing the project.

Suggested screenshots:

- Home Page
- Services Section
- Booking Page
- Admin Login
- Admin Dashboard
- Add Service
- Edit Service

---

# 🔒 Security Features

- Session-based Authentication
- Protected Admin Dashboard
- Database Connection Validation
- Image Upload Support
- Responsive Forms

---

# 🚀 Future Improvements

- Online Payment Integration
- Customer Login
- Booking Status Tracking
- Email Notifications
- SMS Notifications
- Search & Filters
- Service Categories
- Admin Analytics Dashboard
- Customer Reviews
- Google Maps Integration

---

# 💻 Developed Using

- Visual Studio Code
- GitHub Copilot
- XAMPP
- phpMyAdmin

---

# 📖 Learning Objectives

This project demonstrates practical implementation of:

- CRUD Operations
- Core PHP
- MySQL Database
- Bootstrap 5
- Responsive Web Design
- Session Management
- File Upload
- Form Handling
- Database Connectivity

---

# 👩‍💻 Author

**Mehwish Qamar**

PHP Web Developer

---

# 📜 License

This project is created for **educational and learning purposes**.

Free to use for study and academic submission.

---

# ⭐ Thank You

If you found this project helpful, don't forget to ⭐ star the repository on GitHub.