# CineManage - Laporan Manajemen Film Bioskop (UAS Rekayasa Web)

Aplikasi berbasis web untuk mengelola data film bioskop dengan tampilan antarmuka ala Netflix. Proyek ini dibuat sebagai syarat kelulusan Ujian Akhir Semester (UAS) mata kuliah Rekayasa Web.

## Informasi Pengembang
- Nama: Dimas Alfa Pratama
- NIM: 241011750041
- Mata Kuliah: Rekayasa Web

## Deskripsi Proyek
CineManage adalah platform manajemen data film bioskop yang responsif, interaktif, dan modern. Aplikasi ini memiliki dua area utama:
1. Public Frontend: Antarmuka ramah pengguna dengan tampilan premium ala Netflix, dilengkapi fitur pencarian, filter genre, serta tampilan detail poster film.
2. Admin Dashboard: Panel manajemen khusus administrator yang terproteksi autentikasi untuk melakukan operasi CRUD (Create, Read, Update, Delete) data film secara lengkap, serta melakukan ekspor laporan berformat PDF.

## Fitur Utama
1. Autentikasi Admin: Pengamanan dashboard admin menggunakan sistem login berbasis session.
2. CRUD Film Lengkap:
   - Menambahkan film baru beserta file poster film.
   - Melihat daftar film lengkap dalam bentuk tabel administratif.
   - Mengubah informasi film dan mengganti file poster.
   - Menghapus film dan secara otomatis menghapus berkas poster terkait dari disk storage.
3. Ekspor Laporan PDF: Menghasilkan dokumen laporan PDF formal berisi tabel data film lengkap dengan gambar poster masing-masing film.
4. Optimasi Pemuatan Gambar PDF:
   - Pemuatan gambar lokal menggunakan path absolut filesystem server (menggunakan fungsi storage_path) untuk mencegah terjadinya deadlock HTTP loopback pada server development single-threaded.
   - Integrasi opsi isRemoteEnabled pada DomPDF untuk mendukung pemuatan gambar eksternal (CDN/IMDb).

## Struktur Database (Tabel films)
Kolom yang tersedia pada tabel films:
- id_film (Primary Key)
- judul (String)
- genre (String)
- sutradara (String)
- tahun_rilis (Integer)
- gambar (String / Path Gambar Poster)
- created_at & updated_at (Timestamp)

## Panduan Instalasi dan Penggunaan

1. Clone repositori ini ke komputer lokal Anda.
2. Jalankan instalasi dependensi PHP:
   ```bash
   composer install
   ```
3. Salin file konfigurasi lingkungan dan sesuaikan nama database:
   ```bash
   copy .env.example .env
   ```
   Pastikan pengaturan database di file `.env` disesuaikan:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_uas_241011750041
   DB_USERNAME=root
   DB_PASSWORD=
   ```
4. Buat database baru di MySQL dengan nama `db_uas_241011750041`.
5. Generate application key:
   ```bash
   php artisan key:generate
   ```
6. Jalankan migrasi dan seeder database untuk mengisi data awal film beserta akun administrator:
   ```bash
   php artisan migrate:fresh --seed
   ```
   Akun Admin Default:
   - Username: admin
   - Password: admin123
7. Lakukan symlink storage agar gambar lokal dapat diakses oleh publik:
   ```bash
   php artisan storage:link
   ```
8. Instal dependensi frontend (NPM) dan kompilasi aset:
   ```bash
   npm install
   npm run build
   ```
9. Jalankan server lokal:
   ```bash
   php artisan serve
   ```
10. Akses aplikasi melalui peramban pada alamat http://127.0.0.1:8000.

## Menjalankan Pengujian (Testing)
Aplikasi ini dilengkapi dengan pengujian otomatis untuk memvalidasi alur autentikasi admin, pembuatan data film, serta ekspor PDF:
```bash
php artisan test
```
