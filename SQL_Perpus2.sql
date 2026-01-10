-- ==============================
-- DATABASE PERPUSTAKAAN
-- ==============================
DROP DATABASE IF EXISTS db_perpustakaan;
CREATE DATABASE db_perpustakaan;
USE db_perpustakaan;

-- ==============================
-- TABEL KARYAWAN / PETUGAS
-- ==============================
CREATE TABLE karyawan (
    id_karyawan VARCHAR(10) PRIMARY KEY,
    nama_karyawan VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'Petugas') DEFAULT 'Petugas',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==============================
-- TABEL ANGGOTA
-- ==============================
DROP TABLE IF EXISTS anggota;
CREATE TABLE anggota (
    id_anggota VARCHAR(10) PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    alamat TEXT,
    no_telp VARCHAR(20),
    email VARCHAR(100),
    tgl_bergabung DATE
);
CREATE TABLE user_login (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Anggota','Petugas','Admin') NOT NULL,
    ref_id VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- ==============================
-- TABEL KATEGORI
-- ==============================
CREATE TABLE kategori (
    id_kategori VARCHAR(10) PRIMARY KEY,
    nama_kategori VARCHAR(50) NOT NULL
);

-- ==============================
-- TABEL KOLEKSI
-- ==============================
CREATE TABLE koleksi (
    id_koleksi VARCHAR(10) PRIMARY KEY,
    judul VARCHAR(100) NOT NULL,
    penulis VARCHAR(100),
    penerbit VARCHAR(100),
    tahun_terbit INT,
    id_kategori VARCHAR(10),
    gambar VARCHAR(255),
    is_deleted TINYINT(1) DEFAULT 0, 
    CONSTRAINT fk_koleksi_kategori
        FOREIGN KEY (id_kategori)
        REFERENCES kategori(id_kategori)
);

-- ==============================
-- TABEL PEMINJAMAN
-- ==============================
CREATE TABLE peminjaman (
    id_peminjaman VARCHAR(10) PRIMARY KEY,
    id_anggota VARCHAR(10) NOT NULL,
    id_karyawan VARCHAR(10),
    tgl_pinjam DATE NOT NULL,
    tgl_kembali DATE NOT NULL,
    status ENUM('Dipinjam', 'Kembali') DEFAULT 'Dipinjam',
    CONSTRAINT fk_peminjaman_anggota
        FOREIGN KEY (id_anggota)
        REFERENCES anggota(id_anggota),
    CONSTRAINT fk_peminjaman_karyawan
        FOREIGN KEY (id_karyawan)
        REFERENCES karyawan(id_karyawan)
);

-- ==============================
-- TABEL DETAIL PEMINJAMAN
-- ==============================
CREATE TABLE detail_peminjaman (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_peminjaman VARCHAR(10) NOT NULL,
    id_koleksi VARCHAR(10) NOT NULL,
    CONSTRAINT fk_detail_peminjaman
        FOREIGN KEY (id_peminjaman)
        REFERENCES peminjaman(id_peminjaman),
    CONSTRAINT fk_detail_koleksi
        FOREIGN KEY (id_koleksi)
        REFERENCES koleksi(id_koleksi)
);
-- ==============================
-- WISHLIST
-- ==============================
CREATE TABLE wishlist (
    id_wishlist INT AUTO_INCREMENT PRIMARY KEY,
    id_anggota VARCHAR(10),
    id_koleksi VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_anggota) REFERENCES anggota(id_anggota),
    FOREIGN KEY (id_koleksi) REFERENCES koleksi(id_koleksi)
);
-- ==============================
-- INDEX
-- ==============================
CREATE INDEX idx_peminjaman_anggota ON peminjaman(id_anggota);
CREATE INDEX idx_detail_koleksi ON detail_peminjaman(id_koleksi);

-- ==============================
-- DUMMY DATA
-- ==============================

INSERT INTO kategori VALUES
('KT001', 'Teknologi'),
('KT002', 'Sains'),
('KT003', 'Sastra'),
('KT004', 'Fiksi'),
('KT005', 'Sejarah'),
('KT006', 'Biografi'),
('KT007', 'Agama'),
('KT008', 'Bisnis & Ekonomi'),
('KT009', 'Pengembangan Diri'),
('KT010', 'Komik & Manga'),
('KT011', 'Kesehatan'),
('KT012', 'Seni & Budaya'),
('KT013', 'Matematika'),
('KT014', 'Hukum'),
('KT015', 'Masakan'),
('KT016', 'Pendidikan'),
('KT017', 'Psikologi'),
('KT018', 'Olahraga'),
('KT019', 'Travel'),
('KT020', 'Sosial & Politik');


INSERT INTO koleksi VALUES
('BK001', 'Haikyuu!! Vol.1', 'Haruichi Furudate', 'Fiksi', 2012, 'KT004', 'havol1.jpg',0),
('BK002', 'Atomic Habits', 'James Clear', 'Gramedia Pustaka Utama', 2019, 'KT009', 'atomic_habits.jpg',0),
('BK003', 'Filosofi Teras', 'Henry Manampiring', 'Buku Kompas', 2018, 'KT017', 'filosofi_teras.jpg',0),
('BK004', 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Lentera Dipantara', 1980, 'KT003', 'bumi_manusia.jpg',0),
('BK005', 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, 'KT003', 'laskar_pelangi.jpg',0),
('BK006', 'Steve Jobs', 'Walter Isaacson', 'Bentang Pustaka', 2011, 'KT006', 'steve_jobs.jpg',0),
('BK007', 'Sapiens: Riwayat Singkat Humankind', 'Yuval Noah Harari', 'KPG', 2011, 'KT005', 'sapiens.jpg',0),
('BK008', 'Rich Dad Poor Dad', 'Robert T. Kiyosaki', 'Gramedia Pustaka Utama', 1997, 'KT008', 'rich_dad.jpg',0),
('BK009', 'Laut Bercerita', 'Leila S. Chudori', 'KPG', 2017, 'KT004', 'laut_bercerita.jpg',0),
('BK010', 'A Brief History of Time', 'Stephen Hawking', 'Bantam Books', 1988, 'KT002', 'brief_history.jpg',0),
('BK011', 'One Piece Vol. 100', 'Eiichiro Oda', 'Elex Media Komputindo', 2021, 'KT010', 'op100.jpg',0),
('BK012', 'The Psychology of Money', 'Morgan Housel', 'Baca', 2020, 'KT017', 'psychology_money.jpg',0),
('BK013', 'Dunia Sophie', 'Jostein Gaarder', 'Mizan', 1991, 'KT017', 'dunia_sophie.jpg',0),
('BK014', 'Madilog', 'Tan Malaka', 'Narasi', 1943, 'KT020', 'madilog.jpg',0),
('BK015', 'Home Cooking', 'Just Try & Taste', 'FMedia', 2018, 'KT015', 'home_cooking.jpg',0),
('BK016', 'Clean Code', 'Robert C. Martin', 'Prentice Hall', 2008, 'KT001', 'clean_code.jpg',0);

INSERT INTO anggota (id_anggota, nama, alamat, no_telp, email, tgl_bergabung) VALUES
('AGT001', 'Hinata Shoyo', 'Jl. Karasuno No. 10', '081234567801', 'hinata@mail.com', '2025-01-01'),
('AGT002', 'Kageyama Tobio', 'Jl. Kitagawa No. 9', '081234567802', 'kageyama@mail.com', '2025-01-02'),
('AGT003', 'Kozume Kenma', 'Jl. Nekoma No. 5', '081234567803', 'kenma@mail.com', '2025-01-05'),
('AGT004', 'Tanjiro Kamado', 'Gn. Sagiri Blok D', '081234567804', 'tanjiro@mail.com', '2025-01-10'),
('AGT005', 'Nezuko Kamado', 'Gn. Sagiri Blok D', '081234567805', 'nezuko@mail.com', '2025-01-10'),
('AGT006', 'Monkey D. Luffy', 'Dermaga East Blue', '081234567806', 'luffy@mail.com', '2025-01-12'),
('AGT007', 'Roronoa Zoro', 'Jl. Nyasar Terus No. 3', '081234567807', 'zoro@mail.com', '2025-01-15'),
('AGT008', 'Mikasa Ackerman', 'Distrik Shiganshina', '081234567808', 'mikasa@mail.com', '2025-01-20'),
('AGT009', 'Eren Yeager', 'Distrik Shiganshina', '081234567809', 'eren@mail.com', '2025-01-20'),
('AGT010', 'Uzumaki Naruto', 'Desa Konoha Gg. Ramen', '081234567810', 'naruto@mail.com', '2025-01-25');

UPDATE user_login 
SET role = 'Admin' 
WHERE username = 'admin';