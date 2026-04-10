# Cara Menjalankan Backend

Project ini adalah **Laravel 13** dengan requirement utama:

- PHP `^8.3`
- Composer
- Node.js dan npm
- MySQL


## 1. Buat File `.env`

Buat file baru bernama `.env` di root project ini, lalu isi minimal seperti berikut:

```env
APP_NAME=Temuin
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=temuin_backend
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

Setelah file `.env` dibuat, generate key:

```bash
php artisan key:generate
```

## 2. Konfigurasi Database MySQL

Buat database MySQL terlebih dulu.

Contoh SQL:

```sql
CREATE DATABASE temuin_backend
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Pastikan bagian ini di `.env` sesuai dengan MySQL lokal:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=temuin_backend
DB_USERNAME=root
DB_PASSWORD=
```

Lalu jalankan migration:

```bash
php artisan migrate
```

## 3. Cara Run Dengan Herd

Gunakan opsi ini jika PHP dan Composer mengikuti environment dari Herd.

### Langkah

1. Masuk ke folder project:

```bash
cd "C:\Users\andre\Documents\software engineering\temuin\backend"
```

2. Install dependency:

```bash
composer install
npm install
```

3. Buat file `.env` manual sesuai contoh pada bagian pertama.

4. Pastikan konfigurasi MySQL di `.env` sudah benar.

5. Generate app key dan migration:

```bash
php artisan key:generate
php artisan migrate
```

6. Jalankan Vite:

```bash
npm run dev
```

7. Jalankan Laravel:

```bash
php artisan serve
```

8. Akses aplikasi:

```text
http://127.0.0.1:8000
```

### Catatan Herd

- Pastikan versi PHP di Herd minimal **8.3**.
- Jika ingin memakai domain lokal dari Herd, daftarkan folder project ini di Herd sesuai setup lokalmu.
- Jika hanya ingin menjalankan API/backend, terminal dengan PHP dari Herd + `php artisan serve` sudah cukup.

## 4. Cara Run Tanpa Herd

Gunakan opsi ini jika PHP, Composer, Node.js, dan MySQL sudah terpasang manual.

### Langkah

1. Masuk ke folder project:

```bash
cd "C:\Users\andre\Documents\software engineering\temuin\backend"
```

2. Cek versi PHP:

```bash
php -v
```

Versi yang dibutuhkan adalah **PHP 8.3 atau lebih tinggi**.

3. Install dependency:

```bash
composer install
npm install
```

4. Buat file `.env` manual sesuai template pada bagian pertama.

5. Pastikan MySQL sudah berjalan dan database sudah dibuat.

6. Generate key dan migration:

```bash
php artisan key:generate
php artisan migrate
```

7. Jalankan backend:

```bash
php artisan serve
```

8. Jalankan Vite di terminal lain:

```bash
npm run dev
```

9. Akses aplikasi:

```text
http://127.0.0.1:8000
```

## 5. Opsi Menjalankan Semua Service Sekaligus

Project ini punya script:

```bash
composer run dev
```

Script tersebut menjalankan:

- `php artisan serve`
- `php artisan queue:listen`
- `php artisan pail`
- `npm run dev`

Pastikan dependency sudah ter-install dan database MySQL sudah siap sebelum menjalankannya.

## 6. Checklist Singkat

- file `.env` sudah dibuat manual
- `APP_KEY` sudah tergenerate
- `DB_CONNECTION=mysql`
- database MySQL sudah dibuat
- credential MySQL di `.env` sudah benar
- `php artisan migrate` berhasil
- `composer install` sudah selesai
- `npm install` sudah selesai
