## Persiapan Project Kasir

1. Local Server Laragon/Xampp
2. Composer
3. Git
4. Node.js
5. php version >= 8.3

_Sisi eksternal_

1. Printer Thermal ukuran 58mm (Sambungkan printer ke komputer/laptop, jika belum terdaftar pada komputer/laptop maka install driver printer terlebih dahulu atau tonton video tutorial di youtube terkait masalah ini) setelah itu salin nama printer yang ada di properties printer dimenu sharing yang telah terdaftar pada komputer/laptop ke dalam menu setting printer pada web kasirnya.

    ~ printer untuk via Kabel akan berjalan hanya pada server local komputer/dalam satu jaringan yang terhubung ke printer (tanpa windows print).
    ~ printer untuk via mobile/bluetooth akan berjalan pada browser langsung yang support bluetooth (Chroom) bukan connect ke komputer.

2. Scanner QR Code dengan Kameran maupun alat scanner (Opsional)

## Setup Project Kasir

Perhatikan untuk menjalankan atau mensetup project ini.

1. Buat database terlebih dahulu
2. Konfigurasikan file .env dengan database yang telah dibuat
3. Jalankan perintah `c` dan `php artisan db:seed` atau `php artisan migrate:fresh --seed` (untuk membuat data default pertama)
4. Jalankan perintah `php artisan shield:generate --all` (untuk generate policy dari semua model)
5. Jalankan perintah `php artisan shield:super-admin` (untuk menambahkan/assign role super_admin ke user tertentu)
6. Jalankan perintah `php artisan storage:link` untuk membuat symlink
7. Jalankan perintah `php artisan serve` untuk menjalankan projek
8. Buka browser dan kunjungi link http://127.0.0.1:8000
9. Login dengan email (admin@gmail.com) dan password (admin123)

Aplikasi siap di gunakan....
