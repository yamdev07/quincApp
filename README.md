# 🛒 Hardware Store Management System

![Laravel](https://img.shields.io/badge/Laravel-10-red)
![PHP](https://img.shields.io/badge/PHP-8.x-blue)
![MySQL](https://img.shields.io/badge/MySQL-Database-orange)
![License](https://img.shields.io/badge/License-MIT-green)

![CI](https://github.com/yamdev07/QuincApp/actions/workflows/ci.yml/badge.svg)

A **professional hardware store management system** designed to manage products, sales, suppliers, customers and stock efficiently.

The application helps small and medium hardware stores **track inventory, manage sales, and monitor business performance in real time**.

---

# ✨ Features

## 📦 Product Management
- Create, update and delete products
- Product categorization
- Purchase and selling price management
- Stock tracking
- Low stock alerts

---

## 💰 Sales Management
- Fast sales registration
- Automatic total calculation
- Cash / credit payments
- Full transaction history
- Invoice generation

---

## 🛒 Purchase Management
- Supplier order creation
- Delivery tracking
- Automatic stock update

---

## 👥 Customer Management
- Customer profiles
- Debt tracking
- Payment history
- Purchase history per customer

---

## 🏢 Supplier Management
- Supplier database
- Order history

---

## 📊 Dashboard
- Daily sales overview
- Total revenue
- Low stock products
- Best selling products
- Recent activities

---

# 🧰 Tech Stack

| Layer | Technology |
|------|------------|
Backend | Laravel |
Frontend | Blade / Bootstrap / JavaScript |
Database | MySQL |
Auth | Laravel Authentication |
Permissions | Spatie Laravel Permission |

---

# ⚙️ Installation

## 1 Clone the project

```bash
git clone https://github.com/yamdev07/QuincApp.git
cd QuincApp
````

## 2 Install dependencies
````
composer install
npm install
npm run build
````

## 3 Configure environment
````
cp .env.example .env
php artisan key:generate
````
- [ ] Configure database credentials in .env.

## 4 Run migrations
````
php artisan migrate --seed
5 Start the server
php artisan serve
````

Application available at

http://127.0.0.1:8000

## 🔐 Roles & Permissions

- Role	Permissions
- Admin	Full access
- Cashier	Sales management
- Stock Manager	Inventory management

### Default account (if seeder enabled)

- Email: admin@example.com
- Password: password
 
## 📁 Project Structure
````
app/
resources/
   views/
routes/
   web.php
database/
   migrations/
   seeders/
public/

````

## 🚀 Roadmap

Planned improvements:

- Multi-tenant architecture

- Subscription system (Free / Premium plans)

- POS interface for faster checkout

- Mobile responsive dashboard

- Advanced analytics

## 🤝 Contributing

- [ ] Contributions are welcome.

- [ ] Fork the project

- [ ] Create a feature branch

- [ ] Commit your changes

- [ ] Open a Pull Request

## 📜 License

This project is licensed under the MIT License

## 👨‍💻 Author

Yoann Yamd

## GitHub:
https://github.com/yamdev07
