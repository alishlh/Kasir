# Pharmacy POS - Sistem Kasir Apotek Multi-Tenant

Sistem Point of Sale (POS) modern yang dirancang khusus untuk manajemen apotek. Aplikasi ini kini mendukung fitur **Multi-Tenant (Multi-Store)**, memungkinkan satu platform untuk mengelola berbagai cabang apotek dengan manajemen data yang terisolasi dan terpusat.

---

## 🚀 Fitur Utama
- **Multi-Tenant / Multi-Store**: Kelola banyak cabang toko dalam satu aplikasi.
- **Point of Sale (POS)**: Antarmuka kasir yang responsif dan cepat.
- **Manajemen Inventori**: Pelacakan stok otomatis, no batch, dan tanggal kadaluarsa.
- **Laporan Keuangan**: Ringkasan penjualan, profit, dan arus kas (Cash Flow) per cabang.
- **Sistem Role & Permission**: Kontrol akses mendalam menggunakan Filament Shield (Spatie Permission).
- **Dukungan Hardware**: Integrasi printer thermal (Bluetooth/Kabel) dan scanner barcode.

---

## 📋 Persyaratan Sistem
Sebelum memulai, pastikan perangkat Anda memenuhi persyaratan berikut:
- **PHP**: >= 8.3
- **Database**: MySQL / MariaDB
- **Tools**: Composer, Node.js (NPM), Git
- **Local Server**: Laragon / Xampp (Direkomendasikan Laragon)

---

## 🛠️ Instalasi & Setup

Ikuti langkah-langkah berikut untuk menjalankan project di lingkungan lokal:

1. **Clone Repository & Install Dependensi**
   ```bash
   git clone https://github.com/alishlh/Kasir.git
   cd Kasir
   composer install
   ```

2. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database Anda.
   ```bash
   cp .env.example .env
   php artisan key:generate
   php artisan storage:link
   ```

3. **Migrasi & Seeding Database**
   Jalankan perintah ini untuk membuat tabel dan mengisi data awal (termasuk data toko contoh).
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Setup Keamanan (Shield)**
   Generate policy dan pastikan role super admin terdaftar.
   ```bash
   php artisan shield:generate --all
   php artisan shield:super-admin
   ```

5. **Menjalankan Aplikasi**
   ```bash
   php artisan serve
   ```
   Akses aplikasi di: `http://127.0.0.1:8000`

---

## 🏗️ Update Fitur: Multi-Tenant Architecture

Update terbaru memperkenalkan sistem **Multi-Tenant** yang memungkinkan isolasi data total antar cabang. Setiap cabang (Store) memiliki data produk, kategori, transaksi, dan setting masing-masing.

### Alur Kerja (Workflow)
1. **Login**: User masuk menggunakan akun terdaftar.
2. **Pemilihan Toko**: Setelah login, user yang memiliki akses ke lebih dari satu toko akan diminta memilih toko yang ingin dikelola.
3. **Dashboard Scoped**: Dashboard dan semua fitur (POS, Laporan, Stok) akan otomatis memfilter data hanya untuk toko yang dipilih.
4. **Tenant Switcher**: User (terutama Owner/Admin) dapat berpindah antar toko melalui menu dropdown di sidebar tanpa perlu logout.

### Dokumentasi Visual
![Pemilihan Toko](file:///home/adminfid/.gemini/antigravity/brain/387000df-9d58-41c8-8744-11f433cd4bbe/.system_generated/click_feedback/click_feedback_1774965151978.png)
*Antarmuka pemilihan toko dan switcher di sidebar.*

![Halaman Kasir](file:///home/adminfid/.gemini/antigravity/brain/387000df-9d58-41c8-8744-11f433cd4bbe/.system_generated/click_feedback/click_feedback_1774965626019.png)
*Halaman Kasir (POS) yang sudah ter-scope per toko.*

---

## 👥 User Demo & Role

Sistem ini menggunakan pembagian akses yang jelas untuk setiap level pengguna. Berikut adalah data demo yang dapat Anda gunakan:

| Role | Email | Password | Hak Akses |
| :--- | :--- | :--- | :--- |
| **Owner (Super Admin)** | `owner@apotek.com` | `password` | Akses penuh ke semua toko, manajemen user, dan pengaturan sistem global. |
| **Kasir Pusat 1** | `kasir.pusat1@apotek.com` | `password` | Akses terbatas hanya pada **Apotek Pusat** (POS, Produk, Inventori). |
| **Kasir Pusat 2** | `kasir.pusat2@apotek.com` | `password` | Akses terbatas hanya pada **Apotek Pusat**. |
| **Kasir Cabang 1** | `kasir.cabang1@apotek.com` | `password` | Akses terbatas hanya pada **Apotek Cabang 1**. |
| **Petugas Pajak** | `pajak@apotek.com` | `password` | Akses hanya untuk melihat Laporan dan Cash Flow di semua toko. |

---

## 🖨️ Dukungan Hardware (Printer & Scanner)

1. **Printer Thermal (58mm)**:
   - **Kabel/Local**: Printer harus terdaftar di sharing menu Windows/Linux. Salin nama thermal printer ke menu **Setting** di dalam aplikasi.
   - **Bluetooth**: Gunakan browser Chrome yang mendukung Web Bluetooth API untuk mencetak langsung dari device mobile.
2. **Scanner Barcode**:
   - Mendukung scanner hardware (USB/Bluetooth) maupun penggunaan kamera device sebagai scanner QR/Barcode.

---

Aplikasi siap digunakan untuk mengoptimalkan operasional apotek Anda!

---

## ❓ FAQ & Penjelasan Teknis

### Mengapa Produk di Toko Baru Kosong (0)?
Saat Anda membuat Toko baru, daftar produk akan dimulai dari nol. Hal ini dikarenakan sistem menggunakan pendekatan **Independent Product Management** per cabang:
- **Katalog Terpisah**: Setiap toko memiliki daftar produk, harga, dan SKU sendiri. Ini sangat berguna jika setiap cabang menjual item yang berbeda atau memiliki kebijakan harga yang berbeda.
- **Stok Terisolasi**: Stok di Apotek Pusat tidak akan tercampur dengan Apotek Cabang.
- **Manajemen Mandiri**: Manajer toko cabang dapat menambah produk baru tanpa mempengaruhi katalog di toko pusat.

**Apakah bisa dibuat Terpusat?**
Secara teknis bisa. Jika Anda menginginkan satu katalog master yang dibagikan ke semua toko namun dengan stok yang berbeda, arsitektur perlu diubah menggunakan tabel pivot (`product_store`) atau tabel inventori terpisah. Namun, untuk implementasi saat ini, kami memprioritaskan fleksibilitas penuh bagi setiap cabang agar dapat beroperasi secara mandiri.
