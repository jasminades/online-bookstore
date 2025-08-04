

# books & beyond – online bookstore web application

## overview

**book & beyond** is a full-stack web application developed as a course project. It is a modern online bookstore, which allows users to browse, explore, and purchase books, while administrators manage content and users through a secure admin panel. The system includes role-based access control, secure login, dynamic frontend components, and a RESTful backend architecture.


---

## features

### frontend

* **responsive design** -> built using Bootstrap 5 for cross-device compatibility
* **dynamic UI components** -> AJAX and JavaScript for interaction without reloading pages
* **SPA Structure** -> clean single-page layout for smooth navigation and user experience.

### backend

* **FlightPHP micro-framework**
* **layered architecture**:
  * **routes** –> handles all API endpoint declarations
  * **services** –> implements business logic and input validation
  * **DAO (Data Access Layer)** –> secures interaction with MySQL using prepared statements
* **middleware**
  * request validation
  * authentication and role-based access
  * logging and error handling
* **JWT authentication** -> role-based session management for Admin and Users
* **password hashing** -> secure credential storage and login verification
* **Swagger/OpenAPI documentation** -> self-documented and testable API endpoints

### database

* **MySQL relational schema** with the entities
* **CRUD operations** for all entities with referential integrity and constraints

---

## technologies used

* **frontend** -> HTML5, CSS3, JavaScript, Bootstrap 5
* **backend** -> PHP 8 (FlightPHP Framework)
* **database** -> MySQL
* **tools** -> Composer, Git, VS Code
* **documentation** -> Swagger / OpenAPI

---

## key functionalities

* **role-based access**

  * admins -> full CRUD on all entities, user management, dashboard access
  * users -> browse books, place orders, post reviews
* **book management** -> admins can add, update and delete books
* **order processing** -> tracks customer orders and their statuses
* **review system** -> users can submit and read reviews
* **authentication & security**
  * JWT login with role-based routing
  * password hashing with `password_hash()`
  * form validation and middleware checks

