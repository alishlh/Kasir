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

1. Clone Repository dan composer install

   ```
   git clone https://github.com/alishlh/Kasir.git
   cd Kasir
   composer install
   ```
   untuk production

   ```
   composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist
   ```
3. Konfigurasikan file .env sesuaikan hotname, username, dan password, generate key dan storage link

   ```
   cp .env.example .env
   php artisan key:generate
   php artisan storage:link
   ```
4. Jalankan perintah `php artisan migrate --seed` atau `php artisan db:seed` atau `php artisan migrate:fresh --seed` (untuk membuat data default pertama)
   
   ```
   php artisan migrate --seed
   ```
5. 
6. Jalankan perintah `php artisan shield:generate --all` (untuk generate policy dari semua model)
7. Jalankan perintah `php artisan shield:super-admin` (untuk menambahkan/assign role super_admin ke user tertentu)
9. Jalankan perintah `php artisan ser` untuk menjalankan projek
10. Buka browser dan kunjungi link http://127.0.0.1:8000
11. Login dengan email (admin@gmail.com) dan password (password)

Aplikasi siap di gunakan....
