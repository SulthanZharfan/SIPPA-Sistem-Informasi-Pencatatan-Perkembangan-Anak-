# SIPPA – Sistem Informasi Pencatatan Perkembangan Anak  
TK Agriananda – Universitas Pancasila

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

# ✔ Checklist Perkembangan Proyek

## 1. Setup & Pondasi
- [x] Instalasi Laravel Project  
- [x] Instalasi Breeze (sistem login)  
- [x] Instalasi Spatie Role Permission  
- [x] Instalasi Filament v4  
- [x] Pembuatan 4 panel (admin, guru, kepsek, wali)  
- [x] Implementasi `canAccessPanel()`  
- [x] Redirect login ke panel sesuai role  
- [x] Konfigurasi multi-panel  

---

## 2. Database & Model
- [x] Migration sesuai ERD  
- [x] Relationship model  
- [x] Seeder Standar Fisik  
- [x] Seeder Indikator Perkembangan  
- [x] Testing database dasar  

---

## 3. Authentication & Authorization
- [x] Login Breeze  
- [x] Logout  
- [x] Role admin, guru, kepsek, wali  
- [x] Proteksi akses panel  
- [x] Redirect otomatis berdasarkan role  
- [x] `canAccessPanel()` pada User  

---

# 4. Panel Admin – Master Data (Filament v4)
Semua dikelola menggunakan Filament v4 Multi-Panel.

- [X] Tahun Ajaran  
- [X] Kelas  
- [X] Guru  
- [X] Wali  
- [X] Siswa  
- [X] Indikator Perkembangan  
- [X] Standar Fisik  
- [X] User Management  

---

# 5. Panel Guru – Filament Panel

### Perkembangan Fisik
- [x] Input data fisik  
- [ ] Hitung status TB/BB/LK otomatis  
- [ ] Status total otomatis  
- [ ] Rekomendasi otomatis  
- [ ] Grafik perkembangan fisik  

### Perkembangan Kognitif
- [x] Input narasi + upload foto  
- [x] Pemilihan indikator  
- [x] Status menunggu / revisi / disetujui  
- [x] Edit data revisi  

### Presensi
- [x] Input presensi harian  
- [x] Rekap harian/mingguan/bulanan  

---

# 6. Panel Kepala Sekolah – Filament Panel
- [ ] Review & Approval perkembangan fisik  
- [ ] Review & Approval perkembangan kognitif  
- [ ] Lihat rekap presensi  
- [ ] Lihat statistik perkembangan  

---

# 7. Panel Wali Murid – Filament Panel
- [ ] Dashboard perkembangan anak  
- [ ] Grafik perkembangan fisik  
- [ ] Narasi kognitif  
- [ ] Presensi  
- [ ] Unduh rapor digital  

---

# 8. Rapor Digital (PDF)
- [ ] Identitas siswa  
- [ ] Grafik fisik  
- [ ] Tabel fisik bulanan  
- [ ] Narasi kognitif  
- [ ] Rekomendasi  
- [ ] Tanda tangan kepala sekolah  
- [ ] Export PDF  

---

# 9. Testing & Finishing
- [ ] Black Box Testing  
- [ ] Cleanup kode  
- [ ] Optimasi query  
- [ ] Dokumentasi final  

---

# 🛠 Teknologi yang Digunakan
- Laravel 12  
- Filament v4 Multi-Panel  
- Laravel Breeze  
- Spatie Laravel Permission  
- MySQL  
- TailwindCSS
- DomPDF / Snappy PDF  

---

# 📌 Arsitektur Sistem
- Semua role memakai Filament Panel.   
- Login → redirect sesuai role → masuk ke panel masing-masing.  
- Panel diamankan oleh `canAccessPanel()`.  
- Setiap fitur dipisah per panel sesuai tanggung jawabnya.

