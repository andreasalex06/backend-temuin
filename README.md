# Backend TemuIN

Backend TemuIN adalah REST API Laravel untuk fitur autentikasi dan item temu/hilang.

## Prasyarat

- PHP 8.3 atau lebih baru
- Composer
- Node.js dan npm
- MySQL

## Setup dari Clone Baru

Clone repo, lalu masuk ke folder project:

```bash
git clone <url-repo>
cd backend-temuin
```

Install dependency:

```bash
composer install
npm install
```

Siapkan file environment:

```bash
cp .env.example .env
php artisan key:generate
```

Untuk Windows PowerShell, gunakan:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

## Setup Database MySQL

Buat database MySQL bernama `temuin`:

```sql
CREATE DATABASE temuin;
```

Pastikan konfigurasi di `.env` sesuai kredensial MySQL lokal:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=temuin
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan migrasi dan seeder:

```bash
php artisan migrate --seed
```

Seeder membuat akun contoh:

```text
NIM: 2301001
Password: temuin123
```

## Storage Link

Jalankan perintah ini agar file upload pada disk `public` bisa diakses lewat URL `/storage/...`:

```bash
php artisan storage:link
```

Jika link sudah pernah dibuat, pesan bahwa link sudah ada bisa diabaikan.

## Menjalankan Server

Jalankan backend:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

API akan tersedia di:

```text
http://localhost:8000/api
```

## Endpoint Utama

- `POST /api/register`
- `POST /api/login`
- `POST /api/logout`
- `GET /api/me`
- `GET /api/items`
- `GET /api/items/search?q=kata-kunci`
- `GET /api/items/{id}`
- `POST /api/items`
- `PUT /api/items/{id}`
- `DELETE /api/items/{id}`

Endpoint `POST /api/items`, `PUT /api/items/{id}`, `DELETE /api/items/{id}`, `GET /api/me`, dan `POST /api/logout` membutuhkan token Sanctum dari login/register.

## Menjalankan Test

```bash
php artisan test
```

Test otomatis memakai SQLite in-memory dari konfigurasi `phpunit.xml`, jadi tidak mengubah database MySQL lokal.

## Build Asset

Jika halaman web atau asset Vite diperlukan:

```bash
npm run build
```
