Collecting workspace information# 🎨 Seni Kolor - E-Commerce Platform

<p align="center">
  <img src="https://via.placeholder.com/400x150/4F46E5/FFFFFF?text=SENI+KOLOR" alt="Seni Kolor Logo" width="400">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap">
</p>


---

## 🌟 Tentang Seni Kolor

**Seni Kolor** adalah platform e-commerce yang menjual produk celana kolor berkualitas tinggi. Platform ini menggabungkan teknologi modern dengan kebutuhan bisnis lokal, memberikan pengalaman berbelanja yang nyaman dan sistem manajemen yang efisien.

### 🎯 Visi & Misi

**Visi:** Menjadi brand celana kolor terdepan di Indonesia yang dikenal karena kualitas, kenyamanan, dan dukungan terhadap UMKM lokal.

**Misi:**
- 🛍️ Memproduksi celana kolor berkualitas tinggi dengan desain modern
- 🤝 Memberikan pengalaman berbelanja yang menyenangkan
- 🚀 Mendukung perkembangan UMKM lokal di Indonesia

---

## ⭐ Fitur Utama

### 🛒 **E-Commerce Core**
- **Katalog Produk** - Tampilan produk yang menarik dengan detail lengkap
- **Keranjang Belanja** - Sistem keranjang yang responsif dan user-friendly
- **Checkout & Payment** - Proses pembayaran yang aman dan mudah

### 👨‍💼 **Admin Dashboard (Filament)**
- **📊 Dashboard Analytics** - Laporan penjualan dan statistik lengkap
- **🏪 UMKM Management** - Kelola data UMKM dan produk
- **📦 Product Management** - CRUD produk dengan upload gambar
- **💳 Transaction Management** - Monitor dan kelola semua transaksi
- **👥 User Management** - Manajemen pengguna dan role
- **🖼️ Payment Proof** - Verifikasi bukti pembayaran

### 🔐 **Multi-Authentication API**
- **🔑 JWT Authentication** - Token-based authentication untuk mobile app
- **🔐 Basic Authentication** - Username/password authentication
- **🗝️ API Key Authentication** - Secure API access dengan key
- **📚 Swagger Documentation** - Dokumentasi API lengkap dan interaktif

### 💰 **Payment System**
- **Bank Transfer** - Sistem pembayaran via transfer bank
- **Payment Proof Upload** - Upload bukti pembayaran dengan validasi
- **WhatsApp Integration** - Komunikasi langsung dengan penjual

---

## 🚀 Demo & Screenshots

### 🏠 **Homepage**
<p align="center">
  <img src="https://via.placeholder.com/800x400/E5E7EB/374151?text=Homepage+Demo" alt="Homepage" width="800">
</p>

### 🛍️ **Product Catalog**
<p align="center">
  <img src="https://via.placeholder.com/800x400/F3F4F6/374151?text=Product+Catalog" alt="Product Catalog" width="800">
</p>

### 📊 **Admin Dashboard**
<p align="center">
  <img src="https://via.placeholder.com/800x400/EFF6FF/1E40AF?text=Admin+Dashboard" alt="Admin Dashboard" width="800">
</p>

---

## 🛠️ Tech Stack

### **Backend**
- ![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=flat&logo=laravel&logoColor=white) **Laravel 10.x** - PHP Framework
- ![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white) **PHP 8.1+** - Server-side Language
- ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white) **MySQL** - Database
- ![Filament](https://img.shields.io/badge/Filament-FFAA00?style=flat) **Filament Admin** - Admin Panel

### **Frontend**
- ![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=flat&logo=bootstrap&logoColor=white) **Bootstrap 5.3** - CSS Framework
- ![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat&logo=javascript&logoColor=black) **Vanilla JavaScript** - Frontend Logic
- ![Font Awesome](https://img.shields.io/badge/Font_Awesome-339AF0?style=flat&logo=fontawesome&logoColor=white) **Font Awesome** - Icons

### **API & Documentation**
- ![Swagger](https://img.shields.io/badge/Swagger-85EA2D?style=flat&logo=swagger&logoColor=black) **L5-Swagger** - API Documentation
- ![JWT](https://img.shields.io/badge/JWT-000000?style=flat&logo=jsonwebtokens&logoColor=white) **JWT Auth** - Token Authentication
- ![Postman](https://img.shields.io/badge/Postman-FF6C37?style=flat&logo=postman&logoColor=white) **Postman** - API Testing

---

## 📋 Prerequisites

Pastikan sistem Anda memiliki:

- ![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat&logo=php&logoColor=white)
- ![Composer](https://img.shields.io/badge/Composer-885630?style=flat&logo=composer&logoColor=white)
- ![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat&logo=mysql&logoColor=white)
- ![Node.js](https://img.shields.io/badge/Node.js-339933?style=flat&logo=nodedotjs&logoColor=white) (untuk asset compilation)

---

## 🚀 Installation Guide

### 1️⃣ **Clone Repository**
```bash
git clone https://github.com/username/seni-kolor.git
cd seni-kolor
```

### 2️⃣ **Install Dependencies**
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3️⃣ **Environment Setup**
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret
```

### 4️⃣ **Database Configuration**
Edit file .env dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=seni_kolor
DB_USERNAME=root
DB_PASSWORD=
```

### 5️⃣ **Database Migration & Seeding**
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE seni_kolor"

# Run migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed
```

### 6️⃣ **Storage Setup**
```bash
# Create storage symlink
php artisan storage:link

# Set permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache
```

### 7️⃣ **Generate API Documentation**
```bash
# Generate Swagger documentation
php artisan l5-swagger:generate
```

### 8️⃣ **Start Development Server**
```bash
# Start Laravel development server
php artisan serve

# In another terminal, compile assets
npm run dev
```

🎉 **Aplikasi siap digunakan di:** `http://localhost:8000`

---

## 👤 Default Accounts

Setelah seeding, Anda dapat login dengan akun berikut:

### 🔑 **Admin Account**
- **Email:** `admin@example.com`
- **Password:** `password`
- **Access:** Full admin privileges

### 🛍️ **Customer Account**
- **Email:** `customer@example.com`
- **Password:** `password`
- **Access:** Customer shopping features

### 🔐 **API Authentication**
- **API Key:** `HQldYrXmCAojpQfYzRxJXqbx9vg9EG4d`
- **JWT:** Login via `/api/login` endpoint
- **Basic Auth:** Use email/password above

---

## 📚 API Documentation

### 🌐 **Swagger UI**
Akses dokumentasi API interaktif di: `http://localhost:8000/api/documentation`

### 🔐 **Authentication Methods**

#### **1. JWT Authentication**
```bash
# Login to get token
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'

# Use token in requests
curl -X GET http://localhost:8000/api/products \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

#### **2. Basic Authentication**
```bash
curl -X GET http://localhost:8000/api/products-basic \
  -u admin@example.com:password
```

#### **3. API Key Authentication**
```bash
curl -X GET http://localhost:8000/api/secure-data \
  -H "X-API-KEY: HQldYrXmCAojpQfYzRxJXqbx9vg9EG4d"
```

---

## 📁 Project Structure

```
seni-kolor/
├── 📁 app/
│   ├── 📁 Filament/           # Admin panel resources
│   ├── 📁 Http/Controllers/   # API & Web controllers
│   ├── 📁 Models/            # Eloquent models
│   └── 📁 Providers/         # Service providers
├── 📁 database/
│   ├── 📁 migrations/        # Database migrations
│   └── 📁 seeders/           # Database seeders
├── 📁 resources/
│   ├── 📁 views/             # Blade templates
│   └── 📁 css/               # Stylesheets
├── 📁 routes/
│   ├── 📄 web.php            # Web routes
│   └── 📄 api.php            # API routes
├── 📁 storage/               # File storage
└── 📁 public/                # Public assets
```

---

## 🧪 Testing dengan Postman

### 📥 **Import Collection**
1. Download Postman Collection
2. Import ke Postman
3. Set environment variables:
   - `base_url`: `http://localhost:8000`
   - `jwt_token`: (akan diisi otomatis setelah login)

### 🔬 **Test Scenarios**

#### **Authentication Tests**
- ✅ User Registration
- ✅ JWT Login/Logout
- ✅ Token Refresh
- ✅ Basic Auth Login
- ✅ API Key Validation

#### **Product Management**
- ✅ Get All Products
- ✅ Get Product Details
- ✅ Create Product (Admin only)
- ✅ Update Product (Admin only)
- ✅ Delete Product (Admin only)

#### **Order & Payment**
- ✅ Add to Cart
- ✅ Checkout Process
- ✅ Payment Proof Upload
- ✅ Order Tracking

---

## 🎨 Key Features Demo

### 🛒 **Shopping Experience**
```php
// Add product to cart
Route::post('/cart/add/{product}', [CartController::class, 'add']);

// Checkout process
Route::post('/checkout/process', [CheckoutController::class, 'process']);

// Payment proof upload
Route::post('/payment/{transaction}/upload', [PaymentController::class, 'store']);
```

### 📊 **Admin Management**
- **Product CRUD** via Filament ProductResource
- **Transaction Management** via Filament TransactionResource
- **User Management** via Filament UserResource

### 🔗 **API Integration**
```php
// JWT Protected Routes
Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/products', [ProductApiController::class, 'index']);
    Route::post('/products', [ProductApiController::class, 'store']);
});

// Basic Auth Routes
Route::group(['middleware' => ['auth.basic']], function () {
    Route::get('/products-basic', [ProductApiController::class, 'indexBasic']);
});
```

---

### 🐛 **Melaporkan Bug**
Gunakan [GitHub Issues](https://github.com/username/seni-kolor/issues) untuk melaporkan bug atau meminta fitur baru.

<p align="center">
  <img src="https://img.shields.io/badge/Made_in-Indonesia-FF0000?style=for-the-badge&logo=data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAGCAYAAADNll3IAAAABHNCSVQICAgIfAhkiAAAAFhJREFUGJVjYMAH/iPLMTAwMBBh0j8Gg6kMDAwMDAwMjAxE6EVzHQMDAwMDAwMjIQIIsYGBgYGBgYERKY4FHQcPxgYGBgYGBgYGFhQGJkZGBgaGf/8ZGBgYAKgoBxmU4p8cAAAAAElFTkSuQmCC" alt="Made in Indonesia">
</p>