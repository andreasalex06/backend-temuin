# Notes Menjalankan Backend dan Temuin

Repo ini terdiri dari:

- `backend`: Laravel API
- `temuin`: Android app (Jetpack Compose) yang berperan sebagai frontend

Integrasi app saat ini mengarah ke:

- Android emulator mengakses host Windows lewat `http://10.0.2.2/`
- App juga mengirim header `Host: backend.test`
- Konfigurasinya ada di [temuin/app/src/main/java/com/example/belajar/data/remote/ApiConfig.kt](C:\Users\andre\Documents\software engineering\temuin-app\temuin\app\src\main\java\com\example\belajar\data\remote\ApiConfig.kt)

Karena itu ada 2 cara run yang masuk akal:

1. Mode `backend.test` tanpa port tambahan, cocok bila pakai Laravel Herd / web server lokal.
2. Mode `php artisan serve`, cocok bila ingin cepat jalan dari terminal.

## 1. Prasyarat

### Backend

- PHP `8.3+`
- Composer
- Node.js + npm
- Database MySQL

Catatan:

- File [backend/.env](C:\Users\andre\Documents\software engineering\temuin-app\backend\.env) saat ini default ke MySQL:
  - `DB_CONNECTION=mysql`
  - `DB_DATABASE=temuin`
  - `DB_USERNAME=root`
  - `DB_PASSWORD=`
- Repo juga sudah punya file [backend/database/database.sqlite](C:\Users\andre\Documents\software engineering\temuin-app\backend\database\database.sqlite), jadi untuk setup lokal paling simpel Anda bisa pindah ke SQLite.

### Frontend Android

- Android Studio
- Android SDK
- Android Emulator
- JDK 11 atau yang kompatibel dengan Android Studio/Gradle project ini

File [temuin/local.properties](C:\Users\andre\Documents\software engineering\temuin-app\temuin\local.properties) sekarang menunjuk SDK ke:

```properties
sdk.dir=C\:\\Users\\andre\\AppData\\Local\\Android\\Sdk
```

## 2. Setup Backend

Masuk ke folder backend:

```powershell
cd "C:\Users\andre\Documents\software engineering\temuin-app\backend"
```

Install dependency:

```powershell
composer install
npm install
```

Kalau belum ada `.env`, copy dari example. Kalau `.env` sudah ada, langkah ini tidak perlu:

```powershell
Copy-Item .env.example .env
```

Generate app key:

```powershell
php artisan key:generate
```

### Opsi database termudah: SQLite

Edit [backend/.env](C:\Users\andre\Documents\software engineering\temuin-app\backend\.env) menjadi:

```env
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=temuin
# DB_USERNAME=root
# DB_PASSWORD=
```

Lalu jalankan:

```powershell
php artisan migrate
php artisan storage:link
```

### Opsi jika tetap memakai MySQL

Pastikan database `temuin` sudah ada di MySQL, lalu jalankan:

```powershell
php artisan migrate
php artisan storage:link
```

`storage:link` penting karena backend mengembalikan URL gambar dalam format `/storage/...`.

## 3. Cara Integrasi yang Direkomendasikan

### Opsi A: Pakai `backend.test` / Laravel Herd

Pakai opsi ini jika backend Anda memang disajikan lewat domain lokal `backend.test` pada port `80`.

Kondisi ini paling sesuai dengan konfigurasi app saat ini:

- `BASE_URL = "http://10.0.2.2/"`
- `HERD_HOST = "backend.test"`

Langkah:

1. Pastikan project backend aktif di Herd atau web server lokal dan bisa diakses dari Windows melalui `http://backend.test`.
2. Pastikan `.env` backend tetap memakai `APP_URL=http://backend.test`.
3. Jalankan migrasi dan `storage:link`.
4. Jalankan Android emulator.
5. Run project `temuin` dari Android Studio.

Jika semua benar, emulator akan request ke `10.0.2.2` tetapi dengan header host `backend.test`, sesuai implementasi di [temuin/app/src/main/java/com/example/belajar/data/remote/RetrofitProvider.kt](C:\Users\andre\Documents\software engineering\temuin-app\temuin\app\src\main\java\com\example\belajar\data\remote\RetrofitProvider.kt).

### Opsi B: Pakai `php artisan serve` di port 8000

Pakai opsi ini jika Anda tidak menggunakan Herd.

Jalankan backend:

```powershell
cd "C:\Users\andre\Documents\software engineering\temuin-app\backend"
php artisan serve --host=0.0.0.0 --port=8000
```

Lalu ubah [temuin/app/src/main/java/com/example/belajar/data/remote/ApiConfig.kt](C:\Users\andre\Documents\software engineering\temuin-app\temuin\app\src\main\java\com\example\belajar\data\remote\ApiConfig.kt) menjadi:

```kotlin
object ApiConfig {
    const val BASE_URL = "http://10.0.2.2:8000/"
    const val HERD_HOST = "10.0.2.2:8000"
    const val API_PREFIX = "api/"
}
```

Setelah itu rebuild dan jalankan app Android.

Catatan:

- `10.0.2.2` adalah alias dari emulator Android ke host Windows.
- Jika memakai device fisik, `10.0.2.2` tidak berlaku. Ganti `BASE_URL` ke IP LAN laptop/PC Anda, misalnya `http://192.168.1.10:8000/`.

## 4. Menjalankan Frontend `temuin`

Masuk ke folder frontend:

```powershell
cd "C:\Users\andre\Documents\software engineering\temuin-app\temuin"
```

### Dari Android Studio

1. Buka folder `temuin` di Android Studio.
2. Tunggu Gradle sync selesai.
3. Jalankan Android Emulator.
4. Pilih device emulator.
5. Klik `Run 'app'`.

### Dari terminal

```powershell
.\gradlew.bat installDebug
```

Namun untuk development harian, Android Studio lebih praktis karena build, logcat, dan emulator sudah terintegrasi.

## 5. Urutan Run Agar Aplikasi Jalan End-to-End

Urutan aman:

1. Jalankan database.
2. Jalankan backend Laravel.
3. Pastikan migrasi sudah sukses.
4. Jalankan `php artisan storage:link`.
5. Buka Android emulator.
6. Run app `temuin`.
7. Test endpoint berikut dari app:
   - register
   - login
   - list item
   - create item
   - update/delete item

Endpoint backend yang dipakai app ada di [backend/routes/api.php](C:\Users\andre\Documents\software engineering\temuin-app\backend\routes\api.php), antara lain:

- `GET /api/items`
- `GET /api/items/search`
- `GET /api/items/{id}`
- `POST /api/register`
- `POST /api/login`
- `POST /api/logout`
- `GET /api/me`
- `POST /api/items`
- `PUT /api/items/{id}`
- `DELETE /api/items/{id}`

## 6. Troubleshooting Singkat

### App Android tidak bisa connect ke backend

Periksa:

- backend benar-benar jalan
- `BASE_URL` sesuai mode yang dipakai
- kalau pakai emulator, gunakan `10.0.2.2`, bukan `localhost`
- kalau pakai `artisan serve`, pastikan port `8000` ikut ditulis di `BASE_URL`

### Register atau login gagal

Periksa:

- tabel database sudah termigrasi
- koneksi database di `.env` benar
- backend tidak error di terminal/log

### Upload/list gambar tidak tampil

Periksa:

```powershell
php artisan storage:link
```

Karena model item membentuk URL gambar dari `/storage/...`, symlink public storage wajib ada.

### Error database saat pertama run

Kalau ingin setup paling cepat, gunakan SQLite dan file `database/database.sqlite` yang sudah ada.

## 7. Ringkasan Praktis

Kalau ingin cepat jalan tanpa banyak ubahan:

1. Backend:

```powershell
cd "C:\Users\andre\Documents\software engineering\temuin-app\backend"
composer install
npm install
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve --host=0.0.0.0 --port=8000
```

2. Frontend:

- ubah `BASE_URL` ke `http://10.0.2.2:8000/`
- ubah `HERD_HOST` ke `10.0.2.2:8000`
- run app dari Android Studio

Kalau Anda memang memakai Laravel Herd, tidak perlu ubah `ApiConfig.kt`; cukup pastikan `backend.test` aktif dan backend bisa diakses di port `80`.
