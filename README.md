

# Book & Beyond – Online Bookstore Web Application

## Overview

**Book & Beyond** is a full-stack web application developed as a course project. It represents a modern online bookstore, allowing users to browse, explore, and purchase books, while administrators manage content and users through a secure admin panel. The system includes role-based access control, secure login, dynamic frontend components, and a RESTful backend architecture.

Developed using **FlightPHP**, **JavaScript**, **Bootstrap**, and **MySQL**, this project showcases clean separation of concerns through service-based backend logic, dynamic routing, and user-friendly interfaces.

---

## Features

### Frontend

* **Responsive Design**: Built using Bootstrap 5 for cross-device compatibility.
* **Dynamic UI Components**: AJAX and JavaScript enable seamless interaction without reloading pages.
* **SPA Structure**: Clean single-page layout for smooth navigation and user experience.

### Backend

* **FlightPHP Micro-Framework**: Lightweight and fast for building RESTful APIs.
* **Layered Architecture**:

  * **Routes** – Handle all API endpoint declarations.
  * **Services** – Implement business logic and input validation.
  * **DAO (Data Access Layer)** – Secure interaction with MySQL using prepared statements.
* **Middleware**:

  * Request validation
  * Authentication and role-based access
  * Logging and error handling
* **JWT Authentication**: Role-based session management for Admin and Users.
* **Password Hashing**: Secure credential storage and login verification.
* **Swagger/OpenAPI Documentation**: Self-documented and testable API endpoints.

### Database

* **MySQL Relational Schema** with the following entities:

  * `Users`
  * `Books`
  * `Categories`
  * `Orders`
  * `Reviews`
* **CRUD Operations** for all entities with referential integrity and constraints.

---

## Technologies Used

* **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
* **Backend**: PHP 8 (FlightPHP Framework)
* **Database**: MySQL
* **Tools**: Composer, Git, VS Code
* **Documentation**: Swagger / OpenAPI

---

## Key Functionalities

* **Role-Based Access**:

  * Admins: Full CRUD on all entities, user management, dashboard access
  * Users: Browse books, place orders, post reviews
* **Book Management**: Admins can add, update, delete, and categorize books
* **Order Processing**: Tracks customer orders and their statuses
* **Review System**: Users can submit and read reviews
* **Authentication & Security**:

  * JWT login with role-based routing
  * Password hashing with `password_hash()`
  * Form validation and middleware checks

