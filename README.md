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
