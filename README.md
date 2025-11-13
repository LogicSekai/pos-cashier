# POS Cashier - Multi-Tenant Point of Sales System

Aplikasi Multi-Tenant POS (Point of Sales) berbasis Laravel Livewire untuk layanan SaaS. Sistem ini memiliki 2 dashboard utama: **Superadmin** dan **Tenant** dengan manajemen role dan permissions untuk setiap user.

## Fitur Utama

### Superadmin Dashboard
- 🔐 **Autentikasi Superadmin** - Login terpisah untuk superadmin
- 👥 **User Management** - Mengelola user dengan berbagai role (superadmin, admin, dll)
- 🏢 **Tenant Management** - Membuat, mengelola, dan menghapus tenant
- 📊 **Dashboard Statistik** - Melihat total tenant dan user

### Tenant Dashboard
- 🔐 **Autentikasi Tenant** - Login terpisah untuk setiap tenant
- 💰 **POS System** - Sistem kasir untuk transaksi penjualan
- 📦 **Product Management** - Mengelola produk dengan kategori, harga, dan stok
- 📂 **Category Management** - Mengelola kategori produk
- 📈 **Sales Report** - Laporan penjualan dengan filter tanggal
- 👤 **Role Management** - Role untuk kasir, gudang, dan lainnya

## Teknologi

- **Laravel 12** - PHP Framework
- **Livewire 3** - Full-stack framework untuk Laravel
- **Stancl/Tenancy** - Multi-tenancy package
- **Spatie Laravel Permission** - Role & Permission management
- **SQLite** - Database (dapat diganti dengan MySQL/PostgreSQL)

## Instalasi

### Instalasi Lokal (Development)

1. Clone repository
```bash
git clone https://github.com/LogicSekai/pos-cashier.git
cd pos-cashier
```

2. Install dependencies
```bash
composer install
npm install
```

3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Jalankan migrasi
```bash
php artisan migrate
php artisan migrate --path=database/migrations/tenant
```

5. Seed data awal
```bash
php artisan db:seed --class=SuperadminSeeder
```

6. Jalankan aplikasi
```bash
php artisan serve
```

### Instalasi dengan Docker (Production)

1. Clone repository
```bash
git clone https://github.com/LogicSekai/pos-cashier.git
cd pos-cashier
```

2. Setup environment
```bash
cp .env.example .env
# Edit .env sesuai konfigurasi VPS Anda
nano .env
```

3. Jalankan setup script
```bash
chmod +x docker/scripts/setup.sh
./docker/scripts/setup.sh
```

Untuk panduan deployment lengkap, lihat [DEPLOYMENT.md](DEPLOYMENT.md)

## Login Credentials

### Superadmin
- **URL**: `http://localhost:8000/superadmin/login`
- **Email**: `superadmin@pos.com`
- **Password**: `password`

### Admin
- **Email**: `admin@pos.com`
- **Password**: `password`

## Struktur Database

### Central Database (untuk Superadmin)
- `users` - User superadmin dan admin
- `tenants` - Daftar tenant
- `domains` - Domain untuk setiap tenant
- `roles` & `permissions` - Role dan permission management

### Tenant Database (terpisah untuk setiap tenant)
- `users` - User tenant (kasir, gudang, dll)
- `categories` - Kategori produk
- `products` - Produk
- `sales` - Transaksi penjualan
- `sale_items` - Detail item penjualan
- `roles` & `permissions` - Role tenant-specific

## Penggunaan

### Membuat Tenant Baru
1. Login sebagai superadmin
2. Buka menu "Tenants"
3. Klik "Create Tenant"
4. Masukkan nama dan domain (subdomain)
5. Tenant baru akan otomatis dibuat dengan database terpisah

### Mengelola Produk
1. Login ke tenant
2. Buka menu "Products"
3. Tambah produk dengan kategori, SKU, harga, dan stok
4. Edit atau hapus produk sesuai kebutuhan

### Transaksi POS
1. Buka menu "POS"
2. Cari produk atau pilih dari daftar
3. Klik produk untuk menambahkan ke cart
4. Atur jumlah, diskon, dan pajak
5. Pilih metode pembayaran
6. Klik "Complete Sale" untuk menyelesaikan transaksi

### Laporan Penjualan
1. Buka menu "Sales Report"
2. Filter berdasarkan tanggal
3. Lihat statistik dan detail transaksi

## Role dan Permissions

### Superadmin Roles
- `superadmin` - Akses penuh ke semua fitur
- `admin` - Akses terbatas untuk pengelolaan

### Tenant Roles (Contoh)
- `owner` - Pemilik tenant
- `manager` - Manajer toko
- `cashier` - Kasir
- `warehouse` - Staff gudang

Anda dapat membuat role custom sesuai kebutuhan menggunakan Spatie Laravel Permission.

## Multi-Tenancy

Sistem menggunakan database terpisah untuk setiap tenant. Setiap tenant memiliki:
- Database sendiri
- User dan role terpisah
- Data produk dan transaksi terisolasi

## Deployment

### CI/CD dengan GitHub Actions

Proyek ini dilengkapi dengan CI/CD pipeline otomatis menggunakan GitHub Actions untuk deployment ke VPS dengan Docker.

**Fitur CI/CD:**
- 🔄 Auto-deployment saat push ke branch `master` atau `main`
- 🐳 Docker containerization untuk konsistensi environment
- 🚀 Zero-downtime deployment
- 🔐 Secure deployment via SSH
- 📦 Automatic database migrations
- ⚡ Application optimization (cache, routes, views)

**Setup CI/CD:**
1. Configure GitHub Secrets (lihat [DEPLOYMENT.md](DEPLOYMENT.md))
2. Push ke branch `master` atau `main`
3. GitHub Actions akan otomatis build dan deploy ke VPS

**Manual Deployment:**
```bash
# Jalankan di VPS
./docker/scripts/deploy.sh
```

Untuk panduan lengkap, lihat [DEPLOYMENT.md](DEPLOYMENT.md)

## Kontribusi

Kontribusi sangat diterima! Silakan buat pull request atau issue untuk perbaikan dan fitur baru.

## Lisensi

[MIT License](LICENSE)

## Support

Untuk pertanyaan atau dukungan, silakan buat issue di repository ini.
