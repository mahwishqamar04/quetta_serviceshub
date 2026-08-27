# 🏠 Quetta Services Hub

<p align="center">

  <img src="https://img.shields.io/badge/PHP-Core%20PHP-777BB4?style=for-the-badge&logo=php" />

  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql" />

  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap" />

  <img src="https://img.shields.io/badge/jQuery-JavaScript-0769AD?style=for-the-badge&logo=jquery" />

  <img src="https://img.shields.io/badge/AI-Service%20Advisor-6F42C1?style=for-the-badge" />

  <img src="https://img.shields.io/badge/Status-Completed-success?style=for-the-badge" />

</p>

---

## 📌 Project Overview

**Quetta Services Hub** is a responsive web-based platform developed for a local home services business in **Quetta, Balochistan**.

The website allows customers to explore available home services, view service information, submit service bookings, and contact the business.

The system also provides an **Admin Dashboard** where administrators can manage services, bookings, and customer contact messages.

An **AI Service Advisor** is also included to help customers identify suitable services based on their requirements. The advisor loads available services dynamically from the database through a PHP API and recommends a relevant service based on the customer's message.

The project is developed using **Core PHP, MySQL, Bootstrap 5, HTML5, CSS3, JavaScript, jQuery, and Font Awesome**.

The application follows a simple **Core PHP architecture without using MVC or any PHP framework**, making it easy to understand, maintain, and suitable for educational and academic purposes.

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
* AI Service Advisor
* Service recommendations based on customer requirements
* Direct booking link from AI recommendations

---

## 🤖 AI Service Advisor

The project includes a lightweight **AI Service Advisor** that helps customers find the most suitable service for their needs.

### How It Works

1. Customer opens the AI Service Advisor.
2. Customer describes their problem or requirement.
3. The JavaScript advisor analyzes the customer's message.
4. Available services are loaded dynamically from the database through the PHP API.
5. The advisor checks relevant service keywords.
6. A suitable service is recommended.
7. Service information such as name, price, and description is displayed.
8. The customer can click **Book This Service** to continue to the booking page.

### AI Advisor Features

* Natural-language style customer input
* Keyword-based service matching
* Dynamic service loading
* Database API integration
* Service recommendation
* Service price display
* Service description display
* Direct booking functionality
* Bootstrap-based responsive interface
* Lightweight implementation without requiring an external AI API

### Supported Service Examples

The advisor can recommend services such as:

* House Cleaning
* Home Cleaning
* Plumbing
* Electrical Repair
* AC Repair
* Painting
* Carpentry

The available services are retrieved from the database, so the advisor can work with the services stored in the system.

---

# 🔌 Services API

The AI Service Advisor uses a PHP API to retrieve service information from the MySQL database.

### API Endpoint

```text
api/services.php
```

The API returns service information in **JSON format**.

Example response structure:

```json
{
  "success": true,
  "services": [
    {
      "id": 1,
      "name": "House Cleaning",
      "price": "2500.00",
      "description": "Professional home cleaning service.",
      "image": "cleaning.jpg"
    }
  ],
  "message": "Services loaded successfully."
}
```

This API allows the frontend AI Advisor to use the latest services stored in the database.

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

# 📅 Booking System

Customers can book available services by providing:

* Customer Name
* Phone Number
* Address
* Booking Date
* Selected Service

Each booking is connected to a specific service using a **Foreign Key Relationship**.

The `bookings.service_id` field is related to the `services.id` field.

The AI Service Advisor also provides a direct **Book This Service** option so customers can continue to the booking page after receiving a recommendation.

---

# 💬 Contact Message Management

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
* AI Service Advisor Interface
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

| Technology   | Purpose                                  |
| ------------ | ---------------------------------------- |
| HTML5        | Page Structure                           |
| CSS3         | Styling                                  |
| Bootstrap 5  | Responsive UI                            |
| JavaScript   | AI Advisor and Client-side Functionality |
| jQuery       | UI Effects and Interactions              |
| PHP (Core)   | Backend Development and API              |
| MySQL        | Database Management                      |
| XAMPP        | Local Development Server                 |
| phpMyAdmin   | Database Management                      |
| Font Awesome | Icons                                    |
| VS Code      | Code Editor                              |
| GitHub       | Version Control and Repository           |

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
├── ai-advisor.php
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
├── api/
│   └── services.php
│
├── js/
│   └── ai-advisor.js
│
├── assets/
│   ├── styles.css
│   ├── bootstrap.css
│   └── ai-advisor.css
│
├── images/
│   └── service and website images
│
└── uploads/
    └── uploaded service images
```

> File names may vary slightly depending on the final project files and deployment structure.

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

The AI Service Advisor retrieves service information from this table through the `api/services.php` endpoint.

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

### AI Service Advisor

```text
http://localhost/quetta_serviceshub/ai-advisor.php
```

### Services API

```text
http://localhost/quetta_serviceshub/api/services.php
```

The API should return a JSON response with:

```text
"success": true
```

when the database connection and services API are working correctly.

---

# 📸 Screenshots

Screenshots can be added to this section to demonstrate the completed project.

Suggested screenshots:

* Home Page
* About Us Page
* Services Page
* Booking Page
* Contact Page
* AI Service Advisor
* AI Service Recommendation
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
├── ai-advisor.png
├── ai-recommendation.png
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
* API response validation

> For a production-level system, additional security improvements such as password hashing, CSRF protection, stronger validation, secure file-upload validation, API security, and rate limiting are recommended.

---

# 🚀 Future Improvements

The project can be further improved by adding:

* Integration with a more advanced AI model/API
* More intelligent natural-language understanding
* Conversation memory for the AI Advisor
* Voice-based AI Service Advisor
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
* REST-style PHP API
* JSON Data Handling
* Dynamic Database Data Loading
* Keyword-based Service Recommendation
* AI Service Advisor Concept
* Bootstrap 5
* Responsive Web Design
* JavaScript
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
