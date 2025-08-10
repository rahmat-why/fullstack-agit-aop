# 📚 Fullstack AGIT AOP - Laravel Library Management

This is a Laravel-based library management system with two types of users: **Librarian** and **Member**.  

**Librarian**
***Role Access:***
Categories — Manage book categories.

Books — Add, update, and remove books.

Loans — Manage loan transactions for all members.

Users — Add, update, and remove user accounts.

***Example User:***

Email: andi.prasetyo@perpus.com

Password: password123


**Member**

***Role Access:***

Loans — View their own loan history and borrow books.

***Example User:***

Email: budi.santoso@member.com

Password: password123

---

## 🚀 How to Run This Project

Follow these steps to set up and run the project locally.

### 1️⃣ Clone the Repository
```bash
git clone https://github.com/rahmat-why/fullstack-agit-aop.git
cd fullstack-agit-aop
```

### 2️⃣ Install Dependencies
```bash
composer install
npm install
```

### 3️⃣ Configure Environment
Copy the example `.env` file and update it according to your database setup.
```bash
cp .env.example .env
```
Edit .env to set your database credentials:
```bash
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### 4️⃣ Generate Application Key
```bash
php artisan key:generate
```

### 5️⃣ Run Database Migration
```bash
php artisan migrate
```

### 6️⃣ Seed Initial Data
This will create sample users (librarian and member).
```bash
php artisan db:seed
```

### 7️⃣ Serve the Application
```bash
php artisan serve
```
Your application will be available at:
http://localhost:8000
