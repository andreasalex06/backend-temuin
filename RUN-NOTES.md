# Run Notes Backend TemuIN

Catatan ini merangkum urutan paling aman untuk menjalankan backend setelah repo di-clone.

## 1. Install Dependency

```bash
composer install
npm install
```

## 2. Siapkan Environment

```bash
cp .env.example .env
php artisan key:generate
```

Di Windows PowerShell:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

## 3. Siapkan MySQL

Buat database:

```sql
CREATE DATABASE temuin;
```

Lalu sesuaikan `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=temuin
DB_USERNAME=root
DB_PASSWORD=
```

Jika username atau password MySQL berbeda, ubah `DB_USERNAME` dan `DB_PASSWORD`.

## 4. Migrasi, Seeder, dan Storage

```bash
php artisan migrate --seed
php artisan storage:link
```

Akun contoh dari seeder:

```text
NIM: 2301001
Password: temuin123
```

## 5. Jalankan Server

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Base URL:

```text
http://localhost:8000/api
```

Jika diakses dari Android emulator, host Windows biasanya memakai:

```text
http://10.0.2.2:8000/api
```

## 6. Cek Cepat

```bash
php artisan route:list --path=api
php artisan test
```

Test memakai SQLite in-memory sesuai `phpunit.xml`, sehingga aman dijalankan tanpa mengubah data MySQL.

## 7. Troubleshooting

- Jika koneksi database gagal, pastikan service MySQL aktif dan database `temuin` sudah dibuat.
- Jika migrasi gagal karena tabel sudah ada, gunakan database kosong atau jalankan `php artisan migrate:fresh --seed` hanya untuk data lokal yang boleh dihapus.
- Jika upload gambar tidak bisa diakses, jalankan ulang `php artisan storage:link`.
- Jika endpoint autentikasi gagal, cek hasil login/register dan gunakan token Bearer untuk route yang dilindungi Sanctum.
