# ✔ Checklist Fitur - Sistem Pencatatan Perkembangan Anak

## 1. Setup & Pondasi
- [X] Instalasi Laravel Project
- [X] Setup GitHub repo
- [X] Instalasi Jetstream/Breeze
- [ ] Template admin panel (optional: Filament)
- [ ] Struktur folder controller, model, service, view

## 2. Database & Model
- [ ] Migration semua tabel sesuai ERD
- [ ] Model + relationship
- [ ] Seeder Data Standar Fisik
- [ ] Testing database

## 3. Authentication & Authorization (LOGIN SYSTEM)
- [ ] Halaman Login
- [ ] Validasi login (email/username + password)
- [ ] Logout
- [ ] Tambah field role di users
- [ ] Middleware role-based (admin, guru, kepsek, wali)
- [ ] Redirect dashboard sesuai role
- [ ] Proteksi route berdasarkan role
- [ ] Tampilan dashboard awal (admin/guru/kepsek/wali)

## 4. Master Data (Admin)
- [ ] CRUD Data Siswa
- [ ] CRUD Data Guru
- [ ] CRUD Data Wali Murid
- [ ] CRUD Data Kelas
- [ ] CRUD Tahun Ajaran
- [ ] CRUD Akun
- [ ] CRUD Indikator Perkembangan
- [ ] CRUD Data Standar Fisik Anak
- [ ] Backup & Restore Data
- [ ] Log Aktivitas

## 5. Pencatatan Guru - Perkembangan Fisik
- [ ] Form input fisik
- [ ] Hitung status TB/BB/LK otomatis
- [ ] Tentukan status_fisik_total otomatis
- [ ] Generate rekomendasi otomatis
- [ ] Simpan & validasi
- [ ] Tampilan detail perkembangan fisik
- [ ] Grafik fisik (TB/BB/LK)
- [ ] Panel notifikasi kondisi fisik anak

## 6. Pencatatan Guru - Perkembangan Kognitif
- [ ] Form input narasi
- [ ] Upload foto anak
- [ ] Indikator perkembangan
- [ ] Status: menunggu, revisi, disetujui
- [ ] Halaman detail perkembangan kognitif

## 7. Revisi Data
- [ ] Guru dapat membuka data perlu revisi
- [ ] Edit & submit ulang

## 8. Presensi Harian
- [ ] Input presensi siswa
- [ ] Rekap harian/mingguan/bulanan

## 9. Kepala Sekolah
- [ ] Review perkembangan fisik (approve/revisi)
- [ ] Review perkembangan kognitif (approve/revisi)
- [ ] Lihat rekap presensi
- [ ] Lihat rekap perkembangan

## 10. Wali Murid
- [ ] Lihat grafik perkembangan fisik
- [ ] Lihat panel notifikasi fisik
- [ ] Lihat narasi kognitif
- [ ] Lihat presensi anak
- [ ] Unduh rapor digital

## 11. Rapor Digital (PDF)
- [ ] Identitas siswa
- [ ] Grafik fisik
- [ ] Tabel fisik bulanan
- [ ] Narasi kognitif
- [ ] Rekomendasi kondisi fisik
- [ ] Tanggal & Tanda tangan kepsek
- [ ] Export pdf

## 12. Testing & Finishing
- [ ] Black Box Testing
- [ ] Fix bug
- [ ] Rapikan UI
- [ ] Rapikan kode & dokumentasi
