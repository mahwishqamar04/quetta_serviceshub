# 🛠️ Quetta Services Hub — Home Solutions Platform

> **Lead Developer:** Mehwish Qamar  
> **Project Version:** 1.0.0  
> **Target Market:** Quetta, Balochistan, Pakistan  
> **Architecture:** Procedural PHP, RESTful JSON API, MySQL Dynamic Schema  

---

## 📌 Executive Summary

**Quetta Services Hub** is a full-stack, responsive web application engineered by **Mehwish Qamar** to streamline household maintenance and local service bookings in Quetta, Balochistan. The platform connects residents with verified professionals for plumbing, electrical repairs, AC servicing, house cleaning, painting, and carpentry.

The platform combines traditional server-side rendering with asynchronous client-side interaction, including a custom **AI Service Advisor**—a client-side recommendation engine that analyzes natural language queries from users to suggest appropriate services dynamically without requiring paid external API keys.

---

## ✨ Key System Features

### 👤 Customer Features
* **Interactive Service Catalog:** Browse active service categories with transparent PKR pricing dynamically fetched from MariaDB.
* **Smart Booking Pipeline:** Direct appointment scheduling with client/server date constraint checks (preventing historical date selection).
* **AI Service Advisor:** Natural language query processor that performs weighted keyword matching to suggest matching services with live pricing and direct booking links.
* **Customer Contact Gateway:** Interactive contact form saving inquiries to the backend alongside expandable Bootstrap accordions for FAQs.

### 🛡️ Administrative Features (Control Panel)
* **Secure Authentication:** Session-based authorization utilizing PHP `password_verify()` against hashed database credentials.
* **Real-time Analytics:** Statistical counter cards displaying Total Services, Active Customer Bookings, and Pending Contact Inquiries.
* **Complete Service CRUD:** Create services with high-resolution image uploads, update pricing/descriptions, or delete listings.
* **Order & Inquiry Management:** View customer booking records, filter by service relationships, and safely purge old messages.

---

## 🛠️ Technology Stack & Dependencies

| Component | Technology | Usage in Project |
| :--- | :--- | :--- |
| **Frontend Layout** | HTML5 | Semantic markup structure, ARIA accessibility attributes, dynamic templates |
| **Styling** | CSS3 & Bootstrap 5.3.3 | Custom CSS, CSS Grid/Flexbox, dynamic animations, Bootstrap responsive layout system |
| **Client Scripting** | JavaScript (ES6) & jQuery 3.7.1 | Dynamic AI Chat UI, animated statistical counters, client-side validation logic |
| **Backend Core** | PHP 8.2 (Procedural) | Data rendering, session management, file upload handler, REST JSON endpoint |
| **Database** | MariaDB 10.4 / MySQL | Hosted on XAMPP (Port 3307), foreign key cascade bindings (`utf8mb4`) |
| **Media & Icons** | Font Awesome 6.5.2 | Scalable vector UI icons throughout user and admin interfaces |

---

## 🗄️ Database Schema Specification

The application runs on the **`quettaserviceshub_db`** schema:

```sql
services (id [PK], name, description, price, image)
   │
   └───< bookings (id [PK], service_id [FK], name, phone, address, booking_date, created_at)
         [ON DELETE CASCADE]

admins (id [PK], username, password) [Standalone Admin Credentials]

contact_messages (id [PK], first_name, last_name, email, phone, subject, message, created_at)