# 📚 Libook – Sistem Informasi Perpustakaan

Libook adalah aplikasi manajemen perpustakaan berbasis web yang dibangun menggunakan **CodeIgniter 3** dengan arsitektur **MVC** dan paradigma **Object-Oriented Programming (OOP)**. Proyek ini dikembangkan untuk memenuhi **standar kompetensi Skema Sertifikasi Programmer**.

---

## 🛠️ Spesifikasi Teknis

- **Framework** : CodeIgniter 3.1.x (MVC Architecture)
- **Bahasa Pemrograman** : PHP (Object-Oriented Programming)
- **Database** : MySQL
- **Web Server** : Apache (XAMPP/Laragon)

---

## 📂 Struktur Folder (MVC)

```
application/
├── controllers/   # Alur logika & kontrol aplikasi (Auth.php, Admin.php, dll)
├── models/        # Logika akses database (M_Auth.php, M_Buku.php, dll)
├── views/         # Tampilan antarmuka pengguna (UI)
├── config/        # Konfigurasi database & base URL
```

---

## 🚀 Fitur Utama

- 🔐 **Autentikasi User**  
  Login multi-level (Admin & User) dengan enkripsi password (`password_hash` & `password_verify`).

- 📚 **Katalog Buku**  
  Menampilkan seluruh koleksi buku dari database secara dinamis.

- ❤️ **Wishlist Buku**  
  User dapat menambahkan dan menghapus buku dari wishlist.

- 📖 **Sistem Peminjaman Buku**  
  - Durasi pinjam otomatis **7 hari (H+7)** dari tanggal pinjam
  - Riwayat peminjaman tercatat

- 🆔 **Auto ID Anggota**  
  Pembuatan ID anggota otomatis dengan format `AGTxxx`.

---

## ⚙️ Cara Instalasi

1. Pindahkan folder **LIBRARY** ke dalam folder `htdocs`.
2. Buka file konfigurasi database:
   ```
   application/config/database.php
   ```
3. Sesuaikan `hostname`, `username`, dan `password` MySQL.
4. Import file database (`.sql`) ke **phpMyAdmin**.
5. Jalankan aplikasi melalui browser:
   ```
   http://localhost/LIBRARY/
   ```
---

## 🧪 Testing

### 1. Autentikasi Login Admin

![Login Admin](screenshots/login_admin.png)

**Objective**  
Autentikasi Login Admin

**Steps**
1. Buka halaman login  
2. Masukkan username & password admin  
3. Klik tombol Login  

**Expected Result**  
Success and Open dashboard  

**Result**  
✅ SUCCESS

---

### 2. Cek Katalog Buku

![Cek Buku](screenshots/cek_buku.png)

**Objective**  
Cek Katalog Buku

**Steps**
1. Buka menu Katalog  
2. Lihat daftar buku  

**Expected Result**  
Menampilkan seluruh koleksi perpustakaan dari database MySQL secara dinamis  

**Result**  
✅ SUCCESS

---

### 3. Tambah Katalog Buku

![Menambah Buku](screenshots/menambah_buku.png)

**Objective**  
Tambah Katalog Buku

**Steps**
1. Buka menu Katalog  
2. Lihat daftar buku  
3. Klik tombol Tambah Buku  

**Expected Result**  
Menambahkan buku ke dalam katalog  

**Result**  
✅ SUCCESS

---

### 4. Cek Peminjaman Buku

![Cek Peminjaman](screenshots/cek_peminjaman.png)

**Objective**  
Cek Peminjaman Buku

**Steps**
1. Buka menu Peminjaman  
2. Lihat daftar peminjaman  

**Expected Result**  
Menampilkan seluruh peminjaman perpustakaan dari database MySQL secara dinamis  

**Result**  
✅ SUCCESS

---

### 5. Tambah Peminjaman Buku Manual

![Peminjaman Manual](screenshots/peminjaman_manual.png)

**Objective**  
Tambah Peminjaman Buku Manual

**Steps**
1. Buka menu Peminjaman  
2. Lihat daftar peminjaman  
3. Klik tombol Tambah Peminjaman  

**Expected Result**  
Menambahkan peminjaman ke dalam katalog  

**Result**  
✅ SUCCESS

---

### 6. Menyelesaikan Peminjaman Buku

![Selesai Peminjaman](screenshots/selesai_peminjaman.png)

**Objective**  
Menyelesaikan peminjaman buku

**Steps**
1. Buka menu Peminjaman  
2. Lihat daftar peminjaman  
3. Klik tombol Selesai  

**Expected Result**  
Status berubah menjadi selesai  

**Result**  
✅ SUCCESS

---

### 7. Cek Data Anggota

![Data Anggota](screenshots/data_anggota.png)

**Objective**  
Cek Data Anggota

**Steps**
1. Buka menu Data Anggota  
2. Lihat daftar anggota  

**Expected Result**  
Menampilkan seluruh anggota perpustakaan dari database MySQL secara dinamis  

**Result**  
✅ SUCCESS

---

### 8. Sign Up Anggota Baru

![Signup](screenshots/signup.png)

**Objective**  
Sign Up Anggota Baru

**Steps**
1. Buka halaman `signup.php`  
2. Isi data diri lengkap  
3. Klik tombol **Daftar**  

**Expected Result**  
Data tersimpan di tabel anggota & user_login dan sistem me-redirect ke katalog  

**Result**  
✅ SUCCESS

---

### 9. Login User

![Login User](screenshots/login.png)

**Objective**  
Login User

**Steps**
1. Masukkan username & password  
2. Klik tombol Login  

**Expected Result**  
`password_verify()` berhasil, session role dibuat, dan masuk ke katalog  

**Result**  
✅ SUCCESS

---

### 10. Tambah ke Wishlist

![Wishlist](screenshots/wishlist.png)

**Objective**  
Tambah ke Wishlist

**Steps**
1. Buka katalog buku  
2. Klik tombol **Tambah ke Wishlist**  

**Expected Result**  
Buku muncul di halaman `wishlist.php` milik user yang sedang login  

**Result**  
✅ SUCCESS

---

### 11. Peminjaman Buku

![Riwayat Peminjaman](screenshots/riwayat.png)

**Objective**  
Peminjaman Buku

**Steps**
1. Klik **Pinjam** pada buku di katalog / wishlist  
2. Klik **Konfirmasi Pinjam**  

**Expected Result**  
Transaksi tercatat dengan `tgl_kembali` otomatis H+7 dan buku muncul di Riwayat  

**Result**  
✅ SUCCESS

---

### 12. Hapus Wishlist

![Hapus Wishlist](screenshots/hapus_wishlist.png)

**Objective**  
Hapus Wishlist

**Steps**
1. Buka menu Wishlist  
2. Klik tombol **Hapus** pada buku  

**Expected Result**  
Data terhapus dari tabel wishlist dan tidak tampil di halaman tersebut  

**Result**  
✅ SUCCESS


---

## 👨‍💻 Author

Dikembangkan sebagai bagian dari **Skema Sertifikasi Programmer**.


