# 📚 Fullstack AGIT AOP - Laravel Library Management

This is a Laravel-based library management system with two types of users: **Librarian** and **Member**.  

**List Menu**

***Menu Categories***
<img width="1920" height="980" alt="image" src="https://github.com/user-attachments/assets/f439b83a-cc48-42c0-8c80-9192e8a6d5b7" />

***Menu Books***
<img width="1920" height="995" alt="image" src="https://github.com/user-attachments/assets/044e6dc2-1008-49af-a469-6b6de41f8046" />

***Menu Loans***
<img width="1920" height="989" alt="image" src="https://github.com/user-attachments/assets/2c6c7844-fab6-46bf-a926-4d7226919784" />

***Menu Users***
<img width="1920" height="961" alt="image" src="https://github.com/user-attachments/assets/30b8caee-b737-449d-858a-416b72912023" />


**List Access**

**Librarian:** Categories, Books, Loans, and Users

Email: rahmat@librarian.com

Password: password123


**Member:** Loans

Email: yanto@member.com

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
