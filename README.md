# 🏫 Resource Tracker System

A **web-based Resource Management System** built using **PHP, MySQL, JavaScript, and AJAX**.
This system allows institutions to efficiently manage and allocate resources such as classrooms, laboratories, seminar halls, and auditoriums with **role-based access control (RBAC)**.

---

## 🚀 Features

### 🔐 Authentication & Security

* User login system (MD5 password hashing)
* Session-based authentication
* Role-based access control (RBAC)

### 👥 User Roles

* **Super Admin** – Full system control
* **Admin / Facility Manager** – Resource & booking management
* **HOD** – Department-level booking control
* **Faculty** – Book resources
* **Viewer (Student)** – View bookings

---

### 🏢 Resource Management

* Add, edit, delete resources
* Resource types:

  * Classroom
  * Laboratory
  * Auditorium
  * Seminar Hall
* Auto-generated resource codes (CR001, LAB002, etc.)

---

### 📅 Booking System

* Book resources based on:

  * Department
  * Class (FY/SY/TY)
  * Division (A/B/C)
* Time-slot based booking (7:30 AM – 7:30 PM)
* Prevent double booking

---

### ✅ Approval System

* Booking approval workflow (Admin / HOD)
* Pending / Approved / Rejected status

---

### 📊 Dashboard & Reports

* View resource usage
* Booking summary
* Filter-based reports

---

### 📋 Resource Listing

* DataTables integration (server-side processing)
* Pagination, sorting, filtering
* AJAX-based dynamic loading

---

### 🧭 Sidebar Navigation

* Dynamic sidebar based on user role
* Modules:

  * Resources
  * Bookings
  * Approvals
  * Users
  * Reports
  * Settings

---

## 🛠️ Tech Stack

| Technology | Usage                   |
| ---------- | ----------------------- |
| PHP        | Backend logic           |
| MySQL      | Database                |
| JavaScript | Frontend interaction    |
| jQuery     | AJAX & DOM manipulation |
| DataTables | Table handling          |
| HTML/CSS   | UI design               |
| Bootstrap  | Styling (optional)      |

---

## 📂 Project Structure

```
ResourceTracker/
│
├── admin/
│   ├── resource_list.php
│   ├── resource_ajax.php
│   ├── resource_save.php
│   ├── update_resource.php
│   ├── resource_delete.php
│
├── database/
│   ├── db_connect.php
│
├── login/
│   ├── index.php
│   ├── logout.php
│
├── header/
│   ├── header.php
│   ├── footer.php
│
└── assets/
```

---

## ⚙️ Installation

1. Clone the repository:

```bash
git clone https://github.com/your-username/resource-tracker.git
```

2. Move to XAMPP `htdocs`:

```bash
C:\xampp\htdocs\ResourceTracker
```

3. Create database:

```sql
CREATE DATABASE tcet_rt;
```

4. Import SQL tables

5. Configure database:

```php
host: localhost
user: root
password: ""
database: tcet_rt
port: 3307
```

6. Start Apache & MySQL

7. Open in browser:

```
http://localhost/ResourceTracker
```

---

## 🔑 Default Roles

| Role        | Access                     |
| ----------- | -------------------------- |
| Super Admin | Full access                |
| Admin       | Resource + Booking control |
| HOD         | Department booking         |
| Faculty     | Book resources             |
| Viewer      | View only                  |

---

## 🧠 Key Concepts

* RBAC (Role-Based Access Control)
* AJAX-based DataTables
* Soft delete (is_active flag)
* Auto-generated codes
* Clean MVC-like structure

---

## 🔒 Security Notes

* Passwords stored using MD5 (for academic use)
* Use `password_hash()` for production
* Input sanitization implemented

---

## 🚀 Future Enhancements

* Email notifications
* Calendar view (timetable)
* Resource availability heatmap
* Mobile responsive UI
* API integration

---

## 👨‍💻 Author

**Tilak Gupta**

---

## 📜 License

This project is for **educational purposes**.

---
