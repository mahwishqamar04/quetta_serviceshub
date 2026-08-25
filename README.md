# 🏠 Quetta Services Hub

<p align="center">

  <img src="https://img.shields.io/badge/PHP-Core%20PHP-777BB4?style=for-the-badge&logo=php" />

  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql" />

  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap" />

  <img src="https://img.shields.io/badge/jQuery-JavaScript-0769AD?style=for-the-badge&logo=jquery" />

  <img src="https://img.shields.io/badge/Status-Completed-success?style=for-the-badge" />

</p>

---

## 📌 Project Overview

**Quetta Services Hub** is a responsive web-based platform developed for a local home services business in **Quetta, Balochistan**.

The website allows customers to explore available home services, view service information, submit service bookings, and contact the business.

The system also provides an **Admin Dashboard** where administrators can manage services, bookings, and customer contact messages.

The project is developed using **Core PHP, MySQL, Bootstrap 5, HTML5, CSS3, jQuery, and Font Awesome**.

The application follows a simple **Core PHP architecture without using MVC or any PHP framework**, making it easy to understand, maintain, and suitable for educational and academic purposes.

---

## 🌐 Live Website

The project is available online as a live website.

**Live Website:** Quetta Services Hub

---

# ✨ Main Features

## 👨‍💼 Customer Module

* Responsive Home Page
* About Us Page
* Services Page
* View available home services
* Responsive service cards
* Service images
* Service descriptions
* Service pricing
* Online service booking
* Contact Form
* Mobile-friendly interface

---

## 🔐 Admin Module

* Secure Admin Login
* Session-based Authentication
* Admin Dashboard
* Add New Services
* Edit Services
* Delete Services
* Upload Service Images
* Booking Management
* Contact Message Management
* Logout System

---

## 📅 Booking System

Customers can book available services by providing:

* Customer Name
* Phone Number
* Address
* Booking Date
* Selected Service

Each booking is connected to a specific service using a **Foreign Key Relationship**.

The `bookings.service_id` field is related to the `services.id` field.

---

## 💬 Contact Message Management

The website includes a Contact Us form where customers can send messages to the business.

The Admin Dashboard allows the administrator to:

* View contact messages
* Review customer information
* Read submitted messages
* Delete contact messages

Contact messages are stored in the `contact_messages` database table.

---

# 🎨 User Interface

The project provides a modern and responsive user interface including:

* Responsive Home Page
* Responsive About Us Page
* Responsive Services Page
* Responsive Contact Page
* Responsive Booking Page
* Bootstrap 5 Layout
* jQuery Effects
* Sticky Navigation Bar
* Professional Footer
* Responsive Service Cards
* Beautiful Forms
* Font Awesome Icons
* Mobile-friendly Design
* Consistent Color Scheme
* User-friendly Navigation

---

# 🛠 Technology Stack

| Technology   | Purpose                        |
| ------------ | ------------------------------ |
| HTML5        | Page Structure                 |
| CSS3         | Styling                        |
| Bootstrap 5  | Responsive UI                  |
| JavaScript   | Client-side Functionality      |
| jQuery       | UI Effects and Interactions    |
| PHP (Core)   | Backend Development            |
| MySQL        | Database Management            |
| XAMPP        | Local Development Server       |
| phpMyAdmin   | Database Management            |
| Font Awesome | Icons                          |
| VS Code      | Code Editor                    |
| GitHub       | Version Control and Repository |

---

# 📂 Project Structure

```text
quetta_serviceshub/
│
├── index.php
├── about.php
├── services.php
├── contact.php
├── book.php
│
├── admin_login.php
├── admin_dashboard.php
├── edit_service.php
├── logout.php
│
├── config.php
├── README.md
├── quettaserviceshub_db.sql
│
├── assets/
│   ├── styles.css
│   └── bootstrap.css
│
├── images/
│   └── service and website images
│
└── uploads/
    └── uploaded service images
```

---

# 🗄 Database

## Database Name

```text
quettaserviceshub_db
```

The project uses MySQL as the database management system.

---

## 📋 Database Tables

The project uses the following tables:

1. `admins`
2. `services`
3. `bookings`
4. `contact_messages`

---

## 👤 admins Table

| Field    |
| -------- |
| id       |
| username |
| password |

This table stores administrator login information.

---

## 🛠 services Table

| Field       |
| ----------- |
| id          |
| name        |
| description |
| price       |
| image       |

This table stores information about the services offered by the business.

---

## 📅 bookings Table

| Field        |
| ------------ |
| id           |
| service_id   |
| name         |
| phone        |
| address      |
| booking_date |
| created_at   |

The `service_id` field connects each booking with a specific service.

### Foreign Key Relationship

```text
bookings.service_id
        ↓
services.id
```

This relationship ensures that each booking belongs to a valid service.

---

## 💬 contact_messages Table

| Field      |
| ---------- |
| id         |
| name       |
| email      |
| message    |
| created_at |

This table stores messages submitted through the Contact Us form.

---

# 🔑 Default Admin Login

| Username | Password |
| -------- | -------- |
| admin    | 1234     |

> **Note:** For a production website, the default password should be changed to a strong password.

---

# ⚙ Installation Guide

## 1. Install XAMPP

Install XAMPP on your computer.

Start the following services:

* Apache
* MySQL

---

## 2. Copy Project

Copy the project folder into:

```text
xampp/htdocs/
```

The project folder should be:

```text
xampp/htdocs/quetta_serviceshub/
```

---

## 3. Create Database

Open phpMyAdmin from your browser.

Create a database named:

```text
quettaserviceshub_db
```

---

## 4. Import Database

The project includes the SQL database file:

```text
quettaserviceshub_db.sql
```

Import this file into the `quettaserviceshub_db` database using phpMyAdmin.

This will create the required database tables and relationships.

---

## 5. Configure Database

Open:

```text
config.php
```

Check the database connection details.

Example:

```php
$host = "localhost";
$dbname = "quettaserviceshub_db";
$username = "root";
$password = "";
```

Update the credentials if your MySQL configuration is different.

---

## 6. Run Project

Open the project in your browser through XAMPP.

### Homepage

```text
http://localhost/quetta_serviceshub/index.php
```

### Admin Login

```text
http://localhost/quetta_serviceshub/admin_login.php
```

---

# 📸 Screenshots

Screenshots can be added to this section to demonstrate the completed project.

Suggested screenshots:

* Home Page
* About Us Page
* Services Page
* Service Details
* Booking Page
* Contact Page
* Admin Login
* Admin Dashboard
* Add Service
* Edit Service
* Booking Management
* Contact Message Management

Example:

```text
screenshots/
├── home.png
├── about.png
├── services.png
├── booking.png
├── contact.png
├── admin-login.png
└── admin-dashboard.png
```

---

# 🔒 Security Features

The project includes basic security and validation features such as:

* Session-based Admin Authentication
* Protected Admin Dashboard
* Database Connection Validation
* Form Validation
* Image Upload Handling
* Admin Logout
* Foreign Key Relationship
* Controlled Admin Access

> For a production-level system, additional security improvements such as password hashing, CSRF protection, stronger validation, and secure file-upload validation are recommended.

---

# 🚀 Future Improvements

The project can be further improved by adding:

* Online Payment Integration
* Customer Registration and Login
* Booking Status Tracking
* Email Notifications
* SMS Notifications
* Search and Filters
* Service Categories
* Admin Analytics Dashboard
* Customer Reviews and Ratings
* Google Maps Integration
* Customer Booking History
* Password Reset System
* Advanced Admin Reports

---

# 💻 Development Tools

This project was developed using:

* Visual Studio Code
* GitHub
* GitHub Copilot
* XAMPP
* phpMyAdmin
* MySQL
* Web Browser

---

# 📖 Learning Objectives

This project demonstrates practical implementation of:

* Core PHP Development
* MySQL Database Management
* CRUD Operations
* Database Connectivity
* Foreign Key Relationships
* Form Handling
* Booking System
* Contact Form
* Contact Message Management
* Admin Authentication
* Session Management
* File Upload
* Bootstrap 5
* Responsive Web Design
* jQuery
* HTML5
* CSS3

---

# 👩‍💻 Author

**Mehwish Qamar**

PHP Web Developer

---

# 📜 License

This project is created for **educational and learning purposes**.

It is free to use for study, practice, and academic submission.

---

# ⭐ Thank You

Thank you for visiting **Quetta Services Hub**.

If you found this project helpful, consider giving the repository a ⭐ star on GitHub.
