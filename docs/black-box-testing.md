# Hasil Black Box Testing
## SIPPA - Sistem Informasi Pencatatan Perkembangan Anak
### TK Agriananda - Universitas Pancasila

---

# 1. PENGUJIAN LOGIN DAN AUTENTIKASI

## Tabel 1.1. Hasil Black-Box Testing Login Akun

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Mengakses halaman login | Buka URL `/login` | Sistem menampilkan halaman login dengan form email dan password. | Sesuai. Sistem berhasil menampilkan halaman login. |
| 2. | Tidak mengisi kolom email dan password, klik Login | Email: (kosong), Password: (kosong) | Sistem menampilkan pesan error "Please fill out this field" pada kolom email. | Sesuai. Sistem berhasil menampilkan pesan kesalahan pada kolom. |
| 3. | Tidak mengisi kolom password dan klik Login | Email: admin@sippa.test, Password: (kosong) | Sistem menampilkan pesan error pada kolom password bahwa kolom wajib diisi. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 4. | Mengisi email dengan format salah | Email: adminsippa (tanpa @), Password: password | Sistem menampilkan pesan error format email tidak valid. | Sesuai. Sistem berhasil menolak input email tidak valid. |
| 5. | Login dengan email yang tidak terdaftar | Email: tidakada@sippa.test, Password: password | Sistem menampilkan pesan "These credentials do not match our records." | Sesuai. Sistem berhasil menolak login dan menampilkan pesan error. |
| 6. | Login dengan password salah | Email: admin@sippa.test (benar), Password: salahpassword (salah) | Sistem menampilkan pesan error kredensial tidak cocok. | Sesuai. Sistem berhasil menolak login dengan password salah. |
| 7. | Login dengan kredensial valid role Admin | Email: admin@sippa.test (benar), Password: password (benar) | Sistem menerima login dan redirect ke panel `/admin`. | Sesuai. Sistem berhasil login dan redirect ke panel admin. |
| 8. | Login dengan kredensial valid role Guru | Email: guru@sippa.test (benar), Password: password (benar) | Sistem menerima login dan redirect ke panel `/guru`. | Sesuai. Sistem berhasil login dan redirect ke panel guru. |
| 9. | Login dengan kredensial valid role Kepsek | Email: kepsek@sippa.test (benar), Password: password (benar) | Sistem menerima login dan redirect ke panel `/kepsek`. | Sesuai. Sistem berhasil login dan redirect ke panel kepsek. |
| 10. | Login dengan kredensial valid role Wali | Email: wali@sippa.test (benar), Password: password (benar) | Sistem menerima login dan redirect ke panel `/wali`. | Sesuai. Sistem berhasil login dan redirect ke panel wali. |

## Tabel 1.2. Hasil Black-Box Testing Logout

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Melakukan logout dari sistem | Klik tombol Logout pada menu user | Sistem mengakhiri sesi dan redirect ke halaman utama `/`. | Sesuai. Sistem berhasil logout dan redirect ke halaman utama. |
| 2. | Mengakses panel setelah logout | Akses URL `/admin` setelah logout | Sistem redirect ke halaman login karena sesi sudah berakhir. | Sesuai. Sistem berhasil memproteksi akses panel. |

## Tabel 1.3. Hasil Black-Box Testing Proteksi Akses Panel

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Guru mengakses panel Admin | Login sebagai Guru, akses `/admin` | Sistem menolak akses dan menampilkan halaman 403 Forbidden. | Sesuai. Sistem berhasil memproteksi panel admin. |
| 2. | Wali mengakses panel Guru | Login sebagai Wali, akses `/guru` | Sistem menolak akses dan menampilkan halaman 403 Forbidden. | Sesuai. Sistem berhasil memproteksi panel guru. |
| 3. | Kepsek mengakses panel Admin | Login sebagai Kepsek, akses `/admin` | Sistem menolak akses dan menampilkan halaman 403 Forbidden. | Sesuai. Sistem berhasil memproteksi panel admin. |
| 4. | Admin mengakses panel sesuai role | Login sebagai Admin, akses `/admin` | Sistem mengizinkan akses dan menampilkan dashboard admin. | Sesuai. Sistem berhasil memberikan akses sesuai role. |

---

# 2. PENGUJIAN PANEL ADMIN

## Tabel 2.1. Hasil Black-Box Testing Dashboard Admin

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Mengakses dashboard admin | Login sebagai Admin, akses `/admin` | Sistem menampilkan dashboard dengan widget statistik, tabel siswa K1/K2, tabel guru/kepsek/wali/kelas. | Sesuai. Sistem berhasil menampilkan dashboard dengan semua widget. |

## Tabel 2.2. Hasil Black-Box Testing Add Data Tahun Ajaran

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Membuka halaman tambah Tahun Ajaran | Klik tombol "New Tahun Ajaran" | Sistem menampilkan form tambah data dengan kolom Tahun Ajaran, Semester, dan toggle Aktif. | Sesuai. Sistem berhasil menampilkan form. |
| 2. | Tidak mengisi kolom Tahun Ajaran dan klik Save | Tahun Ajaran: (kosong), Semester: Ganjil | Sistem menampilkan pesan error "This field is required" pada kolom Tahun Ajaran. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 3. | Tidak memilih Semester dan klik Save | Tahun Ajaran: 2025/2026, Semester: (kosong) | Sistem menampilkan pesan error pada kolom Semester bahwa kolom wajib diisi. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 4. | Mengisi semua kolom dengan benar | Tahun Ajaran: 2025/2026 (benar), Semester: Ganjil (benar), Aktif: Ya | Sistem menerima data dan menyimpan ke database, menampilkan notifikasi "Created". | Sesuai. Sistem berhasil menyimpan data tahun ajaran baru. |

## Tabel 2.3. Hasil Black-Box Testing Edit Data Tahun Ajaran

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Membuka modal Edit Tahun Ajaran | Klik tombol Edit pada data yang ada | Sistem menampilkan form edit dengan data yang sudah ada. | Sesuai. Sistem berhasil menampilkan form edit. |
| 2. | Mengubah data dan klik Save | Tahun Ajaran: 2024/2025 (diubah), Semester: Genap (diubah), klik Save | Sistem menyimpan perubahan dan menampilkan notifikasi "Saved". | Sesuai. Sistem berhasil menyimpan perubahan. |
| 3. | Mengaktifkan Tahun Ajaran baru | Toggle Aktif: Ya pada tahun ajaran baru | Sistem mengaktifkan tahun ajaran baru dan menonaktifkan yang lama (hanya satu yang boleh aktif). | Sesuai. Sistem berhasil mengatur satu tahun ajaran aktif. |

## Tabel 2.4. Hasil Black-Box Testing Delete Data Tahun Ajaran

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Menghapus data Tahun Ajaran | Klik tombol Delete, konfirmasi hapus | Sistem menghapus data dan menampilkan notifikasi "Deleted". | Sesuai. Sistem berhasil menghapus data. |
| 2. | Membatalkan penghapusan | Klik tombol Delete, klik Cancel | Sistem membatalkan penghapusan dan data tetap ada. | Sesuai. Sistem berhasil membatalkan penghapusan. |

## Tabel 2.5. Hasil Black-Box Testing Add Data Kelas

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Membuka halaman tambah Kelas | Klik tombol "New Kelas" | Sistem menampilkan form dengan kolom Nama Kelas, Tingkat Kelas, Tahun Ajaran, dan Guru. | Sesuai. Sistem berhasil menampilkan form. |
| 2. | Tidak mengisi Nama Kelas | Nama: (kosong), Tingkat: Kelompok A, Tahun Ajaran: 2025/2026, klik Save | Sistem menampilkan pesan error pada kolom Nama Kelas. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 3. | Tidak mengisi Tingkat Kelas | Nama: A1, Tingkat: (kosong), Tahun Ajaran: 2025/2026, klik Save | Sistem menampilkan pesan error pada kolom Tingkat Kelas. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 4. | Tidak memilih Tahun Ajaran | Nama: A1, Tingkat: Kelompok A, Tahun Ajaran: (kosong), klik Save | Sistem menampilkan pesan error pada kolom Tahun Ajaran. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 5. | Mengisi semua kolom dengan benar | Nama: A1 (benar), Tingkat: Kelompok A (benar), Tahun Ajaran: 2025/2026 (benar), Guru: Bu Ani | Sistem menerima data dan menyimpan ke database, menampilkan notifikasi sukses. | Sesuai. Sistem berhasil menyimpan data kelas baru. |

## Tabel 2.6. Hasil Black-Box Testing Edit dan Delete Data Kelas

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Mengubah data Kelas | Ubah Nama: A2, klik Save | Sistem menyimpan perubahan dan menampilkan notifikasi "Saved". | Sesuai. Sistem berhasil menyimpan perubahan. |
| 2. | Menghapus data Kelas | Klik Delete, konfirmasi hapus | Sistem menghapus data kelas dari database. | Sesuai. Sistem berhasil menghapus data. |

## Tabel 2.7. Hasil Black-Box Testing Add Data Guru

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Membuka halaman tambah Guru | Klik tombol "New Guru" | Sistem menampilkan form dengan kolom Akun Guru, Nama Guru, NIP, No. Telepon, Alamat. | Sesuai. Sistem berhasil menampilkan form. |
| 2. | Tidak memilih Akun Guru | Akun Guru: (kosong), Nama: Bu Ani, klik Save | Sistem menampilkan pesan error pada kolom Akun Guru bahwa kolom wajib diisi. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 3. | Tidak mengisi Nama Guru | Akun Guru: guru@sippa.test, Nama: (kosong), klik Save | Sistem menampilkan pesan error pada kolom Nama Guru. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 4. | Mengisi semua kolom wajib dengan benar | Akun Guru: guru@sippa.test (benar), Nama: Bu Ani (benar), NIP: 123456, Telepon: 08123456789, Alamat: Jl. Merdeka | Sistem menerima data dan menyimpan ke database, menampilkan notifikasi sukses. | Sesuai. Sistem berhasil menyimpan data guru baru. |

## Tabel 2.8. Hasil Black-Box Testing Edit dan Delete Data Guru

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Mengubah data Guru | Ubah Nama: Bu Anita, klik Save | Sistem menyimpan perubahan dan menampilkan notifikasi "Saved". | Sesuai. Sistem berhasil menyimpan perubahan. |
| 2. | Menghapus data Guru | Klik Delete, konfirmasi hapus | Sistem menghapus data guru dari database. | Sesuai. Sistem berhasil menghapus data. |

## Tabel 2.9. Hasil Black-Box Testing Add Data Wali

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Membuka halaman tambah Wali | Klik tombol "New Wali" | Sistem menampilkan form dengan kolom Akun Wali, Panggilan, Nama Wali, No. Telepon, Pekerjaan, Alamat. | Sesuai. Sistem berhasil menampilkan form. |
| 2. | Tidak memilih Akun Wali | Akun Wali: (kosong), Nama: Ahmad, klik Save | Sistem menampilkan pesan error pada kolom Akun Wali bahwa kolom wajib diisi. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 3. | Tidak mengisi Nama Wali | Akun Wali: wali@sippa.test, Nama: (kosong), klik Save | Sistem menampilkan pesan error pada kolom Nama Wali. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 4. | Mengisi semua kolom wajib dengan benar | Akun Wali: wali@sippa.test (benar), Panggilan: Bapak, Nama: Ahmad (benar), Telepon: 08987654321, Pekerjaan: Wiraswasta, Alamat: Jl. Sudirman | Sistem menerima data dan menyimpan ke database, menampilkan notifikasi sukses. | Sesuai. Sistem berhasil menyimpan data wali baru. |

## Tabel 2.10. Hasil Black-Box Testing Edit dan Delete Data Wali

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Mengubah data Wali | Ubah Nama: Ahmad Fadli, klik Save | Sistem menyimpan perubahan dan menampilkan notifikasi "Saved". | Sesuai. Sistem berhasil menyimpan perubahan. |
| 2. | Menghapus data Wali | Klik Delete, konfirmasi hapus | Sistem menghapus data wali dari database. | Sesuai. Sistem berhasil menghapus data. |

## Tabel 2.11. Hasil Black-Box Testing Add Data Siswa

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Membuka halaman tambah Siswa | Klik tombol "New Siswa" | Sistem menampilkan form dengan kolom NISN, Nama Siswa, Tempat Lahir, Jenis Kelamin, Tanggal Lahir, Kelas, Tahun Ajaran, Wali Murid. | Sesuai. Sistem berhasil menampilkan form. |
| 2. | Tidak mengisi NISN | NISN: (kosong), Nama: Andi, klik Save | Sistem menampilkan pesan error pada kolom NISN. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 3. | Tidak mengisi Nama Siswa | NISN: 12345, Nama: (kosong), klik Save | Sistem menampilkan pesan error pada kolom Nama Siswa. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 4. | Tidak memilih Jenis Kelamin | NISN: 12345, Nama: Andi, Jenis Kelamin: (kosong), klik Save | Sistem menampilkan pesan error pada kolom Jenis Kelamin. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 5. | Tidak memilih Tanggal Lahir | NISN: 12345, Nama: Andi, Tanggal Lahir: (kosong), klik Save | Sistem menampilkan pesan error pada kolom Tanggal Lahir. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 6. | Tidak memilih Kelas | NISN: 12345, Nama: Andi, Kelas: (kosong), klik Save | Sistem menampilkan pesan error pada kolom Kelas. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 7. | Mengisi semua kolom wajib dengan benar | NISN: 12345 (benar), Nama: Andi (benar), Tempat Lahir: Jakarta, Jenis Kelamin: Laki-laki, Tanggal Lahir: 2020-01-15, Kelas: A1, Tahun Ajaran: 2025/2026, Wali: Bapak Ahmad | Sistem menerima data dan menyimpan ke database, menampilkan notifikasi sukses. | Sesuai. Sistem berhasil menyimpan data siswa baru. |

## Tabel 2.12. Hasil Black-Box Testing Edit dan Delete Data Siswa

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Mengubah data Siswa | Ubah Nama: Andi Pratama, klik Save | Sistem menyimpan perubahan dan menampilkan notifikasi "Saved". | Sesuai. Sistem berhasil menyimpan perubahan. |
| 2. | Menghapus data Siswa | Klik Delete, konfirmasi hapus | Sistem menghapus data siswa dari database. | Sesuai. Sistem berhasil menghapus data. |

## Tabel 2.13. Hasil Black-Box Testing Add Data Indikator Perkembangan

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Membuka halaman tambah Indikator | Klik tombol "New Indikator Perkembangan" | Sistem menampilkan form dengan kolom Aspek Perkembangan dan Deskripsi Indikator. | Sesuai. Sistem berhasil menampilkan form. |
| 2. | Tidak mengisi Aspek Perkembangan | Aspek: (kosong), Deskripsi: Mampu mengenal huruf, klik Save | Sistem menampilkan pesan error pada kolom Aspek Perkembangan. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 3. | Mengisi semua kolom dengan benar | Aspek: Kognitif (benar), Deskripsi: Mampu mengenal huruf dan angka | Sistem menerima data dan menyimpan ke database, menampilkan notifikasi sukses. | Sesuai. Sistem berhasil menyimpan data indikator baru. |

## Tabel 2.14. Hasil Black-Box Testing Edit dan Delete Data Indikator Perkembangan

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Mengubah data Indikator | Ubah Aspek: Bahasa, klik Save | Sistem menyimpan perubahan dan menampilkan notifikasi "Saved". | Sesuai. Sistem berhasil menyimpan perubahan. |
| 2. | Menghapus data Indikator | Klik Delete, konfirmasi hapus | Sistem menghapus data indikator dari database. | Sesuai. Sistem berhasil menghapus data. |

## Tabel 2.15. Hasil Black-Box Testing Add Data Standar Fisik Anak

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Membuka halaman tambah Standar Fisik | Klik tombol "New Data Standar Fisik Anak" | Sistem menampilkan form dengan kolom Umur (bulan), Jenis Kelamin, TB Min/Max, BB Min/Max, LK Min/Max. | Sesuai. Sistem berhasil menampilkan form. |
| 2. | Tidak mengisi Umur (bulan) | Umur: (kosong), Jenis Kelamin: Laki-laki, klik Save | Sistem menampilkan pesan error pada kolom Umur. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 3. | Tidak memilih Jenis Kelamin | Umur: 60, Jenis Kelamin: (kosong), klik Save | Sistem menampilkan pesan error pada kolom Jenis Kelamin. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 4. | Tidak mengisi kolom TB/BB/LK | Umur: 60, Jenis Kelamin: L, TB Min: (kosong), klik Save | Sistem menampilkan pesan error pada kolom yang kosong. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 5. | Mengisi semua kolom dengan benar | Umur: 60 (benar), Jenis Kelamin: Laki-laki, TB Min: 100, TB Max: 115, BB Min: 15, BB Max: 21, LK Min: 48, LK Max: 52 | Sistem menerima data dan menyimpan ke database, menampilkan notifikasi sukses. | Sesuai. Sistem berhasil menyimpan data standar fisik baru. |

## Tabel 2.16. Hasil Black-Box Testing Add Data User

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Membuka halaman tambah User | Klik tombol "New User" | Sistem menampilkan form dengan kolom Nama, Email, Password, dan Role (checkbox). | Sesuai. Sistem berhasil menampilkan form. |
| 2. | Tidak mengisi Nama | Nama: (kosong), Email: user@sippa.test, Password: password, klik Save | Sistem menampilkan pesan error pada kolom Nama. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 3. | Tidak mengisi Email | Nama: User Baru, Email: (kosong), Password: password, klik Save | Sistem menampilkan pesan error pada kolom Email. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 4. | Mengisi Email dengan format salah | Nama: User Baru, Email: userbaru (tanpa @), Password: password, klik Save | Sistem menampilkan pesan error format email tidak valid. | Sesuai. Sistem berhasil menolak input email tidak valid. |
| 5. | Mengisi Email yang sudah terdaftar | Nama: User Baru, Email: admin@sippa.test (sudah ada), Password: password, klik Save | Sistem menampilkan pesan error "The email has already been taken." | Sesuai. Sistem berhasil menolak duplikat email. |
| 6. | Tidak mengisi Password saat create | Nama: User Baru, Email: userbaru@sippa.test, Password: (kosong), klik Save | Sistem menampilkan pesan error pada kolom Password. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 7. | Mengisi semua kolom dengan benar | Nama: User Baru (benar), Email: userbaru@sippa.test (benar), Password: password (benar), Role: guru | Sistem menerima data dan menyimpan ke database dengan role yang dipilih, menampilkan notifikasi sukses. | Sesuai. Sistem berhasil menyimpan data user baru dengan role. |

## Tabel 2.17. Hasil Black-Box Testing Edit Data User

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Membuka halaman Edit User | Klik tombol Edit pada data user | Sistem menampilkan form edit dengan data yang sudah ada (password kosong). | Sesuai. Sistem berhasil menampilkan form edit. |
| 2. | Mengubah data tanpa mengubah password | Nama: User Diubah (diubah), Password: (kosong), klik Save | Sistem menyimpan perubahan nama dan password tetap seperti sebelumnya. | Sesuai. Sistem berhasil menyimpan perubahan tanpa mengubah password. |
| 3. | Mengubah password | Nama: User Diubah, Password: newpassword (baru), klik Save | Sistem menyimpan password baru dan user dapat login dengan password baru. | Sesuai. Sistem berhasil mengubah password. |
| 4. | Mengubah Role | Role: admin (diubah dari guru), klik Save | Sistem menyimpan role baru dan user dapat mengakses panel sesuai role baru. | Sesuai. Sistem berhasil mengubah role user. |

---

# 3. PENGUJIAN PANEL GURU

## Tabel 3.1. Hasil Black-Box Testing Dashboard Guru

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Mengakses dashboard guru | Login sebagai Guru, akses `/guru` | Sistem menampilkan dashboard dengan widget statistik dan tabel siswa yang diampu. | Sesuai. Sistem berhasil menampilkan dashboard guru. |

## Tabel 3.2. Hasil Black-Box Testing Add Pertemuan Perkembangan Fisik

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Membuka halaman tambah Pertemuan | Klik tombol "New Pertemuan Perkembangan Fisik" | Sistem menampilkan form dengan kolom Kelas, Tahun Ajaran, Pertemuan Ke, Tanggal, Jam Mulai, Jam Selesai. | Sesuai. Sistem berhasil menampilkan form. |
| 2. | Tidak memilih Kelas | Kelas: (kosong), klik Save | Sistem menampilkan pesan error pada kolom Kelas. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 3. | Tidak mengisi Pertemuan Ke | Kelas: A1, Pertemuan Ke: (kosong), klik Save | Sistem menampilkan pesan error pada kolom Pertemuan Ke. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 4. | Mengisi Pertemuan Ke dengan nilai 0 | Kelas: A1, Pertemuan Ke: 0, klik Save | Sistem menampilkan pesan error bahwa nilai minimal adalah 1. | Sesuai. Sistem berhasil menolak nilai di bawah minimum. |
| 5. | Tidak memilih Tanggal | Kelas: A1, Pertemuan Ke: 1, Tanggal: (kosong), klik Save | Sistem menampilkan pesan error pada kolom Tanggal. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 6. | Mengisi semua kolom dengan benar | Kelas: A1 (benar), Tahun Ajaran: 2025/2026 Ganjil (benar), Pertemuan Ke: 1 (benar), Tanggal: 2025-01-06, Jam Mulai: 08:00, Jam Selesai: 10:00 | Sistem menerima data dan menyimpan ke database dengan status "pending", menampilkan notifikasi sukses. | Sesuai. Sistem berhasil menyimpan pertemuan baru. |

## Tabel 3.3. Hasil Black-Box Testing Input Perkembangan Fisik Siswa

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Membuka form input perkembangan fisik | Klik tombol "New Perkembangan Fisik" dalam pertemuan | Sistem menampilkan form dengan kolom Siswa, Tinggi Badan, Berat Badan, Lingkar Kepala, Tanggal Ukur, Umur (bulan), Foto. | Sesuai. Sistem berhasil menampilkan form. |
| 2. | Tidak memilih Siswa | Siswa: (kosong), klik Save | Sistem menampilkan pesan error pada kolom Siswa. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 3. | Tidak mengisi Tinggi Badan | Siswa: Andi, Tinggi Badan: (kosong), Berat Badan: 18, Lingkar Kepala: 50, klik Save | Sistem menampilkan pesan error pada kolom Tinggi Badan. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 4. | Tidak mengisi Berat Badan | Siswa: Andi, Tinggi Badan: 110, Berat Badan: (kosong), Lingkar Kepala: 50, klik Save | Sistem menampilkan pesan error pada kolom Berat Badan. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 5. | Tidak mengisi Lingkar Kepala | Siswa: Andi, Tinggi Badan: 110, Berat Badan: 18, Lingkar Kepala: (kosong), klik Save | Sistem menampilkan pesan error pada kolom Lingkar Kepala. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 6. | Tidak mengisi Tanggal Ukur | Siswa: Andi, Tinggi Badan: 110, Berat Badan: 18, Lingkar Kepala: 50, Tanggal Ukur: (kosong), klik Save | Sistem menampilkan pesan error pada kolom Tanggal Ukur. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 7. | Mengisi semua kolom dengan benar | Siswa: Andi (benar), Tinggi Badan: 110 (benar), Berat Badan: 18 (benar), Lingkar Kepala: 50 (benar), Tanggal Ukur: 2025-01-06 | Sistem menerima data, menghitung kategori TB/BB/LK otomatis, menghitung status ringkas otomatis, dan menyimpan ke database. | Sesuai. Sistem berhasil menyimpan data dengan perhitungan kategori otomatis. |
| 8. | Menghitung umur otomatis | Siswa: Andi (lahir 2020-01-15), Tanggal Ukur: 2025-01-06 | Sistem menghitung umur dalam bulan secara otomatis (60 bulan). | Sesuai. Sistem berhasil menghitung umur otomatis. |
| 9. | Upload foto perkembangan fisik | Siswa: Andi, semua kolom terisi, upload foto .jpg | Sistem menerima file foto dan menyimpan ke direktori storage. | Sesuai. Sistem berhasil menyimpan foto. |
| 10. | Upload file bukan gambar | Siswa: Andi, upload file .pdf | Sistem menolak file dan menampilkan pesan error format file tidak valid. | Sesuai. Sistem berhasil menolak file bukan gambar. |
| 11. | Upload file melebihi ukuran maksimum | Siswa: Andi, upload foto > 2MB | Sistem menolak file dan menampilkan pesan error ukuran file melebihi batas (max 2MB). | Sesuai. Sistem berhasil menolak file terlalu besar. |

## Tabel 3.4. Hasil Black-Box Testing Hasil Perhitungan Kategori Fisik

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Siswa dengan data fisik normal | TB: 110cm (dalam standar), BB: 18kg (dalam standar), LK: 50cm (dalam standar) | Sistem menghitung kategori TB, BB, LK = Normal, status ringkas = Normal. | Sesuai. Sistem berhasil menghitung kategori normal. |
| 2. | Siswa dengan Tinggi Badan di bawah standar | TB: 90cm (di bawah standar), BB: 18kg, LK: 50cm | Sistem menghitung kategori TB = Di Bawah Normal, status ringkas = Perlu Perhatian. | Sesuai. Sistem berhasil mendeteksi TB di bawah normal. |
| 3. | Siswa dengan Berat Badan di atas standar | TB: 110cm, BB: 30kg (di atas standar), LK: 50cm | Sistem menghitung kategori BB = Di Atas Normal, status ringkas = Perlu Perhatian. | Sesuai. Sistem berhasil mendeteksi BB di atas normal. |

## Tabel 3.5. Hasil Black-Box Testing Add Perkembangan Kognitif

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Membuka halaman tambah Perkembangan Kognitif | Klik tombol "New Perkembangan Kognitif" | Sistem menampilkan form dengan kolom Siswa, Indikator Perkembangan, Narasi Perkembangan, Foto (opsional). | Sesuai. Sistem berhasil menampilkan form. |
| 2. | Tidak memilih Siswa | Siswa: (kosong), Indikator: Kognitif, klik Save | Sistem menampilkan pesan error pada kolom Siswa. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 3. | Tidak memilih Indikator | Siswa: Andi, Indikator: (kosong), klik Save | Sistem menampilkan pesan error pada kolom Indikator Perkembangan. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 4. | Tidak mengisi Narasi | Siswa: Andi, Indikator: Kognitif, Narasi: (kosong), klik Save | Sistem menampilkan pesan error pada kolom Narasi Perkembangan. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 5. | Mengisi semua kolom wajib dengan benar | Siswa: Andi (benar), Indikator: Kognitif (benar), Narasi: Anak mampu mengenal huruf A-Z (benar), Foto: (kosong/opsional) | Sistem menerima data dan menyimpan ke database dengan status "menunggu", menampilkan notifikasi sukses. | Sesuai. Sistem berhasil menyimpan data perkembangan kognitif. |
| 6. | Mengisi dengan foto | Siswa: Andi, Indikator: Bahasa, Narasi: Anak aktif bercerita, Foto: foto.jpg | Sistem menerima data dengan foto dan menyimpan ke database. | Sesuai. Sistem berhasil menyimpan data dengan foto. |

## Tabel 3.6. Hasil Black-Box Testing Add Pertemuan Presensi

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Membuka halaman tambah Pertemuan Presensi | Klik tombol "New Pertemuan Presensi" | Sistem menampilkan form dengan kolom Kelas, Pertemuan Ke, Tanggal Pertemuan, Jam Mulai, Jam Selesai. | Sesuai. Sistem berhasil menampilkan form. |
| 2. | Tidak memilih Kelas | Kelas: (kosong), klik Save | Sistem menampilkan pesan error pada kolom Kelas. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 3. | Tidak mengisi Pertemuan Ke | Kelas: A1, Pertemuan Ke: (kosong), klik Save | Sistem menampilkan pesan error pada kolom Pertemuan Ke. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 4. | Mengisi semua kolom dengan benar | Kelas: A1 (benar), Pertemuan Ke: 1 (benar), Tanggal: 2025-01-06, Jam Mulai: 08:00, Jam Selesai: 10:00 | Sistem menerima data dan menyimpan ke database, menampilkan notifikasi sukses. | Sesuai. Sistem berhasil menyimpan pertemuan presensi baru. |

## Tabel 3.7. Hasil Black-Box Testing Input Presensi Siswa

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Membuka form input presensi | Klik tombol "New Presensi" dalam pertemuan | Sistem menampilkan form dengan kolom Siswa, Tanggal, Status Kehadiran. | Sesuai. Sistem berhasil menampilkan form. |
| 2. | Tidak memilih Siswa | Siswa: (kosong), Tanggal: 2025-01-06, Status: Hadir, klik Save | Sistem menampilkan pesan error pada kolom Siswa. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 3. | Tidak memilih Tanggal | Siswa: Andi, Tanggal: (kosong), Status: Hadir, klik Save | Sistem menampilkan pesan error pada kolom Tanggal. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 4. | Tidak memilih Status Kehadiran | Siswa: Andi, Tanggal: 2025-01-06, Status: (kosong), klik Save | Sistem menampilkan pesan error pada kolom Status Kehadiran. | Sesuai. Sistem berhasil menolak dan menampilkan pesan kesalahan. |
| 5. | Mengisi presensi Hadir | Siswa: Andi (benar), Tanggal: 2025-01-06 (benar), Status: Hadir (benar) | Sistem menerima data dan menyimpan ke database dengan status Hadir. | Sesuai. Sistem berhasil menyimpan presensi hadir. |
| 6. | Mengisi presensi Izin | Siswa: Budi, Tanggal: 2025-01-06, Status: Izin | Sistem menerima data dan menyimpan ke database dengan status Izin. | Sesuai. Sistem berhasil menyimpan presensi izin. |
| 7. | Mengisi presensi Sakit | Siswa: Citra, Tanggal: 2025-01-06, Status: Sakit | Sistem menerima data dan menyimpan ke database dengan status Sakit. | Sesuai. Sistem berhasil menyimpan presensi sakit. |
| 8. | Mengisi presensi Alfa | Siswa: Dina, Tanggal: 2025-01-06, Status: Alfa | Sistem menerima data dan menyimpan ke database dengan status Alfa. | Sesuai. Sistem berhasil menyimpan presensi alfa. |

---

# 4. PENGUJIAN PANEL KEPALA SEKOLAH

## Tabel 4.1. Hasil Black-Box Testing Dashboard Kepala Sekolah

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Mengakses dashboard kepsek | Login sebagai Kepsek, akses `/kepsek` | Sistem menampilkan dashboard dengan statistik ringkas. | Sesuai. Sistem berhasil menampilkan dashboard kepsek. |

## Tabel 4.2. Hasil Black-Box Testing Monitoring Perkembangan Fisik

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Melihat daftar pertemuan perkembangan fisik | Akses menu "Perkembangan Fisik" di panel Kepsek | Sistem menampilkan tabel daftar pertemuan dengan kolom Tanggal, Kelas, Guru, Pertemuan, Terisi, Normal, Perlu Perhatian, Status. | Sesuai. Sistem berhasil menampilkan daftar pertemuan. |
| 2. | Melihat detail pertemuan | Klik tombol "Detail" pada pertemuan | Sistem menampilkan halaman detail dengan informasi pertemuan dan daftar siswa beserta data fisiknya. | Sesuai. Sistem berhasil menampilkan detail pertemuan. |
| 3. | Filter berdasarkan status | Pilih filter Status: Menunggu | Sistem menampilkan hanya pertemuan dengan status "pending". | Sesuai. Sistem berhasil memfilter data. |
| 4. | Menyetujui pertemuan perkembangan fisik | Klik tombol "Setujui" pada pertemuan dengan status pending | Sistem mengubah status pertemuan menjadi "approved", mengubah status_persetujuan semua data fisik siswa menjadi "disetujui", dan mencatat waktu approval. | Sesuai. Sistem berhasil menyetujui pertemuan. |
| 5. | Minta revisi pertemuan perkembangan fisik | Klik tombol "Minta Revisi" pada pertemuan | Sistem mengubah status pertemuan menjadi "rejected", mengubah status_persetujuan semua data fisik siswa menjadi "revisi". | Sesuai. Sistem berhasil minta revisi pertemuan. |
| 6. | Tombol Setujui tidak muncul untuk pertemuan yang sudah approved | Pertemuan dengan status "approved" | Tombol "Setujui" tidak tampil pada pertemuan yang sudah disetujui. | Sesuai. Sistem berhasil menyembunyikan tombol. |

## Tabel 4.3. Hasil Black-Box Testing Monitoring Perkembangan Kognitif

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Melihat daftar perkembangan kognitif | Akses menu "Perkembangan Kognitif" di panel Kepsek | Sistem menampilkan tabel daftar perkembangan kognitif dengan kolom Siswa, Indikator, Guru, Tanggal, Status. | Sesuai. Sistem berhasil menampilkan daftar. |
| 2. | Melihat detail perkembangan kognitif | Klik tombol "View" pada data | Sistem menampilkan halaman detail dengan narasi lengkap, foto (jika ada), dan informasi siswa. | Sesuai. Sistem berhasil menampilkan detail. |
| 3. | Menyetujui perkembangan kognitif | Klik tombol "Setujui" pada data dengan status menunggu | Sistem mengubah status menjadi "disetujui", menampilkan notifikasi sukses. | Sesuai. Sistem berhasil menyetujui data. |
| 4. | Minta revisi perkembangan kognitif | Klik tombol "Minta Revisi" pada data | Sistem mengubah status menjadi "revisi", guru dapat melihat dan mengedit data untuk revisi. | Sesuai. Sistem berhasil minta revisi. |

## Tabel 4.4. Hasil Black-Box Testing Rekap Presensi

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Membuka halaman rekap presensi | Akses menu "Rekap Presensi" di panel Kepsek | Sistem menampilkan halaman rekap dengan filter Tahun Ajaran, Kelas, Guru, dan pilihan periode (Harian/Mingguan/Bulanan). | Sesuai. Sistem berhasil menampilkan halaman rekap. |
| 2. | Melihat rekap harian | Pilih Periode: Harian, pilih tanggal | Sistem menampilkan data presensi untuk tanggal yang dipilih. | Sesuai. Sistem berhasil menampilkan rekap harian. |
| 3. | Melihat rekap mingguan | Pilih Periode: Mingguan, pilih minggu | Sistem menampilkan ringkasan presensi untuk minggu yang dipilih. | Sesuai. Sistem berhasil menampilkan rekap mingguan. |
| 4. | Melihat rekap bulanan | Pilih Periode: Bulanan, pilih bulan | Sistem menampilkan ringkasan presensi untuk bulan yang dipilih. | Sesuai. Sistem berhasil menampilkan rekap bulanan. |
| 5. | Filter berdasarkan Kelas | Pilih Kelas: A1 | Sistem menampilkan rekap presensi hanya untuk kelas A1. | Sesuai. Sistem berhasil memfilter berdasarkan kelas. |
| 6. | Melihat detail rekap | Klik tombol "Detail" pada rekap | Sistem menampilkan halaman detail dengan daftar siswa dan status kehadiran masing-masing. | Sesuai. Sistem berhasil menampilkan detail rekap. |

---

# 5. PENGUJIAN PANEL WALI MURID

## Tabel 5.1. Hasil Black-Box Testing Dashboard Wali

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Mengakses dashboard wali | Login sebagai Wali, akses `/wali` | Sistem menampilkan dashboard dengan informasi anak (nama, NIS/NISN, kelas, guru, wali), ringkasan presensi, grafik perkembangan fisik, dan ringkasan kognitif. | Sesuai. Sistem berhasil menampilkan dashboard wali. |
| 2. | Melihat ringkasan presensi | Dashboard menampilkan ringkasan presensi | Sistem menampilkan tabel presensi anak dengan filter periode (mingguan/bulanan/semua). | Sesuai. Sistem berhasil menampilkan ringkasan presensi. |
| 3. | Melihat grafik perkembangan fisik | Dashboard menampilkan grafik | Sistem menampilkan grafik perkembangan fisik anak dengan status terbaru dan rekomendasi. | Sesuai. Sistem berhasil menampilkan grafik. |

## Tabel 5.2. Hasil Black-Box Testing Halaman Presensi Wali

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Mengakses halaman presensi | Klik menu "Presensi" di panel Wali | Sistem menampilkan tabel presensi anak dengan filter periode. | Sesuai. Sistem berhasil menampilkan halaman presensi. |
| 2. | Filter presensi mingguan | Pilih filter: Mingguan | Sistem menampilkan presensi 1 minggu terakhir. | Sesuai. Sistem berhasil memfilter data. |
| 3. | Filter presensi bulanan | Pilih filter: Bulanan | Sistem menampilkan presensi 1 bulan terakhir. | Sesuai. Sistem berhasil memfilter data. |

## Tabel 5.3. Hasil Black-Box Testing Halaman Perkembangan Fisik Wali

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Mengakses halaman perkembangan fisik | Klik menu "Perkembangan Fisik" di panel Wali | Sistem menampilkan grafik perkembangan fisik, tabel data fisik, status terbaru, rekomendasi, dan foto (jika ada). | Sesuai. Sistem berhasil menampilkan halaman perkembangan fisik. |
| 2. | Melihat status terbaru | Halaman menampilkan status | Sistem menampilkan status TB/BB/LK terbaru (Normal/Di Bawah Normal/Di Atas Normal). | Sesuai. Sistem berhasil menampilkan status. |
| 3. | Melihat rekomendasi | Halaman menampilkan rekomendasi | Sistem menampilkan rekomendasi berdasarkan status fisik anak. | Sesuai. Sistem berhasil menampilkan rekomendasi. |

## Tabel 5.4. Hasil Black-Box Testing Halaman Perkembangan Kognitif Wali

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Mengakses halaman perkembangan kognitif | Klik menu "Perkembangan Kognitif" di panel Wali | Sistem menampilkan halaman dengan filter Tahun Ajaran/Semester, ringkasan semester, dan detail per indikator. | Sesuai. Sistem berhasil menampilkan halaman. |
| 2. | Filter Tahun Ajaran | Pilih Tahun Ajaran: 2025/2026 | Sistem menampilkan data perkembangan kognitif untuk tahun ajaran yang dipilih. | Sesuai. Sistem berhasil memfilter data. |
| 3. | Melihat ringkasan semester | Halaman menampilkan ringkasan | Sistem menampilkan total indikator yang dicapai, tanggal dan guru terakhir yang mencatat. | Sesuai. Sistem berhasil menampilkan ringkasan. |
| 4. | Melihat detail per indikator | Klik expand pada indikator | Sistem menampilkan detail narasi perkembangan dan foto (jika ada) untuk indikator tersebut. | Sesuai. Sistem berhasil menampilkan detail. |

## Tabel 5.5. Hasil Black-Box Testing Laporan Semester (PDF)

| No. | Skenario Pengujian | Test Case | Hasil yang Diharapkan | Hasil Pengujian |
|-----|-------------------|-----------|----------------------|-----------------|
| 1. | Mengakses halaman laporan semester | Klik menu "Laporan Semester" di panel Wali | Sistem menampilkan halaman laporan dengan ringkasan identitas siswa, presensi, fisik, dan kognitif. | Sesuai. Sistem berhasil menampilkan halaman laporan. |
| 2. | Melihat nomor laporan otomatis | Halaman menampilkan nomor laporan | Sistem menampilkan nomor laporan yang dihasilkan secara otomatis. | Sesuai. Sistem berhasil menampilkan nomor laporan. |
| 3. | Mencetak laporan PDF | Klik tombol "Cetak PDF" | Sistem mengunduh file PDF laporan semester dengan format yang benar, berisi identitas siswa, rekap presensi, ringkasan fisik, dan ringkasan kognitif per indikator. | Sesuai. Sistem berhasil mengunduh file PDF. |
| 4. | Isi laporan PDF | Buka file PDF yang diunduh | PDF berisi: Identitas siswa lengkap, Rekap presensi, Status fisik terakhir + rekomendasi, Ringkasan kognitif per indikator, Nomor laporan. | Sesuai. Sistem berhasil mencetak laporan lengkap. |

---

# KESIMPULAN PENGUJIAN

Berdasarkan hasil Black Box Testing yang telah dilakukan terhadap Sistem Informasi Pencatatan Perkembangan Anak (SIPPA), dapat disimpulkan bahwa:

1. **Sistem Login dan Autentikasi** berjalan sesuai dengan yang diharapkan, termasuk validasi input, proteksi akses panel berdasarkan role, dan redirect otomatis.

2. **Panel Admin** berhasil mengelola semua master data (Tahun Ajaran, Kelas, Guru, Wali, Siswa, Indikator Perkembangan, Standar Fisik, User Management) dengan validasi form yang lengkap.

3. **Panel Guru** berhasil melakukan pencatatan perkembangan fisik (dengan perhitungan kategori otomatis), perkembangan kognitif, dan presensi siswa dengan validasi yang tepat.

4. **Panel Kepala Sekolah** berhasil melakukan monitoring, approval, dan minta revisi untuk perkembangan fisik dan kognitif, serta melihat rekap presensi dengan berbagai filter.

5. **Panel Wali Murid** berhasil menampilkan informasi anak, presensi, perkembangan fisik dengan grafik, perkembangan kognitif, dan mencetak laporan semester dalam format PDF.

**Semua fitur telah diuji dan berfungsi sesuai dengan yang diharapkan (100% Sesuai).**

