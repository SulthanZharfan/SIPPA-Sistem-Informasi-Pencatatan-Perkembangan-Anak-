# SIPPA - Sistem Informasi Pencatatan Perkembangan Anak
TK Agriananda - Universitas Pancasila

SIPPA adalah aplikasi sistem informasi untuk mengelola pencatatan perkembangan fisik dan kognitif anak TK Agriananda.
Aplikasi dibangun menggunakan **Laravel 12**, **Filament v4 Multi-Panel**, **Breeze**, dan **Spatie Role Permission**.

Sistem menyediakan **4 panel terpisah**:

| Role            | Panel           | Path     |
|-----------------|-----------------|----------|
| Admin           | Filament Panel  | /admin   |
| Guru            | Filament Panel  | /guru    |
| Kepala Sekolah  | Filament Panel  | /kepsek  |
| Wali Murid      | Filament Panel  | /wali    |

Login tetap menggunakan Breeze di `/login`, lalu user diarahkan otomatis ke panel sesuai role.

---

# Ringkasan Fitur (Sesuai Kodingan)

## 1. Panel Admin - Master Data dan User
Semua dikelola menggunakan Filament v4 Multi-Panel.
- [x] Tahun Ajaran
- [x] Kelas
- [x] Guru
- [x] Wali
- [x] Siswa
- [x] Indikator Perkembangan
- [x] Data Standar Fisik Anak
- [x] User Management + assign role (admin, guru, kepsek, wali)
- [x] Dashboard admin + widget ringkas (statistik, tabel siswa K1/K2, tabel guru/kepsek/wali/kelas)

---

## 2. Panel Guru - Pencatatan Harian

### Perkembangan Fisik (berbasis pertemuan)
- [x] Buat/edit pertemuan perkembangan fisik
- [x] Input pengukuran per siswa (TB/BB/LK + foto)
- [x] Hitung kategori TB/BB/LK otomatis
- [x] Status ringkas otomatis + preview hasil

### Perkembangan Kognitif
- [x] Pilih siswa + indikator perkembangan
- [x] Input narasi
- [x] Upload foto (opsional)
- [x] Status persetujuan: menunggu (default), disetujui, revisi

### Presensi (berbasis pertemuan)
- [x] Buat/edit pertemuan presensi
- [x] Input presensi per siswa (hadir/izin/sakit/alfa + keterangan)

---

## 3. Panel Kepala Sekolah - Monitoring dan Approval

### Monitoring Perkembangan Fisik
- [x] Lihat daftar pertemuan + detail per siswa
- [x] Approve / minta revisi
- [x] Status pertemuan: pending / approved / rejected

### Monitoring Perkembangan Kognitif
- [x] Lihat detail narasi + foto
- [x] Approve / minta revisi

### Rekap Presensi
- [x] Harian / Mingguan / Bulanan
- [x] Filter Tahun Ajaran, Kelas, Guru, Periode
- [x] Lihat detail rekap per periode

---

## 4. Panel Wali Murid - Informasi Anak

### Dashboard Wali
- [x] Informasi anak (nama, NIS/NISN, kelas, guru, wali)
- [x] Ringkasan presensi + tabel presensi (filter mingguan/bulanan/semua)
- [x] Grafik perkembangan fisik + status terbaru + rekomendasi
- [x] Ringkasan perkembangan kognitif

### Presensi
- [x] Tabel presensi anak + filter periode

### Perkembangan Fisik
- [x] Grafik dan tabel data fisik
- [x] Status terbaru + rekomendasi
- [x] Foto data terbaru (jika ada)

### Perkembangan Kognitif
- [x] Filter Tahun Ajaran/Semester
- [x] Ringkasan semester (total indikator, tanggal dan guru terakhir)
- [x] Detail per indikator + narasi + foto (expand/collapse)

### Laporan Semester (PDF)
- [x] Identitas siswa
- [x] Rekap presensi
- [x] Ringkasan fisik (status terakhir + rekomendasi)
- [x] Ringkasan kognitif per indikator
- [x] Nomor laporan otomatis
- [x] Export PDF

---

# Checklist Perkembangan Proyek

## 1. Setup dan Pondasi
- [x] Instalasi Laravel Project
- [x] Instalasi Breeze (sistem login)
- [x] Instalasi Spatie Role Permission
- [x] Instalasi Filament v4
- [x] Pembuatan 4 panel (admin, guru, kepsek, wali)
- [x] Implementasi `canAccessPanel()`
- [x] Redirect login ke panel sesuai role
- [x] Konfigurasi multi-panel

---

## 2. Database dan Model
- [x] Migration sesuai ERD
- [x] Relationship model
- [x] Seeder Standar Fisik
- [x] Seeder Indikator Perkembangan
- [x] Testing database dasar

---

## 3. Authentication dan Authorization
- [x] Login Breeze
- [x] Logout
- [x] Role admin, guru, kepsek, wali
- [x] Proteksi akses panel
- [x] Redirect otomatis berdasarkan role
- [x] `canAccessPanel()` pada User

---

# 9. Testing dan Finishing
- [x] Black Box Testing
- [x] Cleanup kode
- [x] Optimasi query
- [x] Dokumentasi final

---

# Teknologi yang Digunakan
- Laravel 12
- Filament v4 Multi-Panel
- Laravel Breeze
- Spatie Laravel Permission
- MySQL
- TailwindCSS
- DomPDF / Snappy PDF

---

# Arsitektur Sistem
- Semua role memakai Filament Panel.
- Login -> redirect sesuai role -> masuk ke panel masing-masing.
- Panel diamankan oleh `canAccessPanel()`.
- Setiap fitur dipisah per panel sesuai tanggung jawabnya.
