# Aplikasi PPDB Online

Aplikasi Pendaftaran Peserta Didik Baru (PPDB) berbasis web dengan Laravel. Sistem ini memungkinkan calon siswa untuk mendaftar secara online dan admin untuk mengelola pendaftaran, tahun ajaran, dan pengguna.

## Fitur Utama

### 🔐 Autentikasi & Authorization

- Login multi-level (Admin & User)
- Middleware role-based access
- Proteksi route berdasarkan hak akses

### 📝 Manajemen Pendaftaran

- Form pendaftaran online dengan validasi
- Generate nomor peserta otomatis (format: PPDB-TAHUN-NO_URUT)
- Upload dokumen pendukung
- Cetak kartu peserta
- Pencarian dan filter data pendaftar
- Pagination data

### 🗑️ Soft Delete & Trash

- Data tidak langsung dihapus permanen
- Fitur trash untuk menyimpan data yang dihapus
- Restore data dari trash
- Force delete permanen
- Restore all & empty trash

### 📅 Manajemen Tahun Ajaran

- CRUD tahun ajaran
- Set status aktif/non-aktif
- Atur kuota pendaftaran per tahun
- Cek kuota otomatis saat pendaftaran

### 👥 Manajemen Pengguna

- CRUD pengguna
- Role management (Admin/User)
- Profil pengguna

## Teknologi

- **Framework:** Laravel 10.x
- **Database:** MySQL
- **Frontend:** Blade Template, Bootstrap 5
- **Authentication:** Laravel Breeze
- **Package:** Laravel Debugbar (development)

## Struktur Database

### Tabel Utama

1. **calon_siswas** - Data pendaftaran
    - Soft Deletes
    - Relasi ke tahun_ajarans
    - Relasi ke dokumens

2. **tahun_ajarans** - Manajemen tahun ajaran
    - Field: tahun_ajaran, kuota, status

3. **dokumens** - Upload file pendukung
    - Field: path, original_name

4. **users** - Manajemen pengguna
    - Field: name, email, password, role

## Route Structure

### Route Penting

```php
// Route yang benar urutannya:
1. Route spesifik: /pendaftaran/trash, /pendaftaran/create
2. Route tetap: /pendaftaran/cetak/{id}
3. Route dinamis: /pendaftaran/{id}
```

## Instalasi (Windows / XAMPP)

### 1) Prasyarat

- **PHP 8.2+** (dijalankan melalui XAMPP atau PHP CLI)
- **Composer** (https://getcomposer.org/)
- **Node.js + npm** (https://nodejs.org/)
- **MySQL / MariaDB** (biasanya sudah tersedia di XAMPP)

> 🔧 Jika menggunakan XAMPP, pastikan Apache dan MySQL sudah dijalankan.

### 2) Siapkan Proyek

```bash
cd C:\xampp\htdocs\spmb-smpkp2
```

### 3) Install Dependensi PHP & JavaScript

```bash
composer install
npm install
```

### 4) Siapkan File Environment

```bash
copy .env.example .env
```

Lalu edit file `.env` untuk menyesuaikan konfigurasi database (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

> Contoh (XAMPP default):
>
> DB_CONNECTION=mysql
> DB_HOST=127.0.0.1
> DB_PORT=3306
> DB_DATABASE=spmb_smpkp2
> DB_USERNAME=root
> DB_PASSWORD=

### 5) Generate App Key dan Migrasi Database

```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

> Jika belum membuat database, buat dulu database MySQL dengan nama yang sesuai (`spmb_smpkp2` atau nama yang kamu gunakan di `.env`).

### 6) Jalankan Aplikasi

#### Opsi A (development - hot reload)

```bash
npm run dev
```

Kemudian kunjungi: http://127.0.0.1:5173 (atau alamat yang ditampilkan di terminal).

#### Opsi B (Laravel built-in server)

```bash
php artisan serve
```

Kemudian kunjungi: http://127.0.0.1:8000

---

## Tips Tambahan

- **Reset data**: `php artisan migrate:fresh --seed`
- **Build production assets**: `npm run build`
- **Cek queue** (dipakai untuk notifikasi/processing): `php artisan queue:work`
