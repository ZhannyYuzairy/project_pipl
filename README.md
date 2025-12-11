# Toko Z&Z - Point of Sale (POS) System

Aplikasi Kasir (Point of Sale) berbasis web modern yang dirancang untuk membantu operasional toko ritel. Aplikasi ini mencakup manajemen stok, transaksi kasir real-time, pengelolaan karyawan, hingga laporan keuangan laba rugi otomatis.

## 📋 Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Prasyarat](#prasyarat)
- [Instalasi](#instalasi)
- [Struktur Proyek](#struktur-proyek)
- [Penggunaan](#penggunaan)
- [Kontribusi](#kontribusi)
- [Lisensi](#lisensi)

## ✨ Fitur Utama

### 👑 Panel Owner (Admin)
- **Dashboard Interaktif**: Ringkasan omzet harian, jumlah transaksi, dan produk aktif secara real-time.
- **Manajemen Produk**: Tambah, edit, dan hapus data barang lengkap dengan fitur Barcode, Harga Beli, Harga Jual, dan Stok.
- **Manajemen Kasir**: Kelola akun pengguna kasir, reset password, dan pengaturan status aktif/non-aktif.
- **Laporan Stok**: Monitoring ketersediaan barang dengan estimasi nilai aset.
- **Laporan Laba & Rugi**: Analisis keuangan otomatis berdasarkan rentang tanggal, menampilkan Total Penjualan, HPP (Modal), dan Laba Bersih.
- **Visualisasi Data**: Bar progress untuk perbandingan visual antara Penjualan vs Modal.

### 🛒 Panel Kasir (POS)
- **Point of Sale Modern**: Antarmuka kasir yang responsif dan mudah digunakan.
- **Scan Barcode**: Dukungan input barang menggunakan scanner barcode atau pencarian manual.
- **Keranjang Belanja**: Kalkulasi subtotal otomatis.
- **Riwayat Transaksi**: Lihat dan cetak ulang struk penjualan harian.
- **Dashboard Kasir**: Ringkasan kinerja kasir pribadi (Total penjualan shift saat ini).

## 🛠 Teknologi yang Digunakan

- **Framework**: CodeIgniter 4 (PHP Framework)
- **Bahasa Pemrograman**: PHP 7.4+
- **Database**: MySQL / MariaDB
- **Frontend**: HTML5, CSS3 (Custom UI / Bootstrap), JavaScript
- **Server**: Apache (XAMPP/Laragon)
- **Tools**: Composer, Git

## 📋 Prasyarat

Sebelum memulai, pastikan server lokal Anda memiliki:

- PHP versi 7.4 atau lebih tinggi (Disarankan PHP 8.1)
- Composer (Terinstal global)
- MySQL/MariaDB
- Ekstensi PHP `intl` dan `mbstring` diaktifkan

## 🚀 Instalasi

1. **Clone repositori**
   ```bash
   git clone [https://github.com/ZhannyYuzairy/project_pipl.git](https://github.com/ZhannyYuzairy/project_pipl.git)
   cd toko-zz-pos

2.  **Instal dependensi**

    ```bash
    composer install
    ```

3.  **Konfigurasi Environment**
    Salin file contoh konfigurasi:

    ```bash
    cp .env.example .env
    ```

    Buka file `.env` dan sesuaikan koneksi database:

    ```ini
    CI_ENVIRONMENT = development

    database.default.hostname = localhost
    database.default.database = db_toko_zz
    database.default.username = root
    database.default.password =
    database.default.DBDriver = MySQLi
    ```

4.  **Setup Database**
    Jalankan migrasi untuk membuat tabel yang diperlukan:

    ```bash
    php spark migrate
    php spark db:seed DemoUserSeeder
    ```

5.  **Jalankan Server**

    ```bash
    php spark serve
    ```

    Akses aplikasi di browser melalui `http://localhost:8080`.

## 📁 Struktur Proyek

```text
POS-zz/
├── app/
│   ├── Controllers/      # Logika Owner & Kasir
│   ├── Database/         # Migrasi struktur tabel
│   ├── Models/           # Model data (Produk, Transaksi, User)
│   ├── Views/            # Antarmuka (Dashboard, POS, Laporan)
│   └── Config/           # Konfigurasi aplikasi
├── public/               # Aset publik
│   ├── css/              # Stylesheet
│   ├── js/               # Skrip interaksi (Ajax/POS logic)
│   └── uploads/          # Gambar produk (jika ada)
├── writable/             # Log & Cache
├── .env                  # Variabel lingkungan
└── spark                 # CLI Tool CodeIgniter
```

## 💡 Penggunaan

### Login

  - **Owner**: Gunakan akun administrator untuk mengakses manajemen penuh.
  - **Kasir**: Gunakan akun kasir untuk mengakses fitur POS.

### Alur Transaksi (Kasir)

1.  Buka menu **POS Kasir**.
2.  Scan barcode produk atau ketik nama barang di kolom pencarian.
3.  Masukkan jumlah (Qty) dan tekan `Enter` atau tombol **Tambah ke cart**.
4.  Masukkan nominal uang yang diterima pelanggan.
5.  Tekan **Selesaikan transaksi & cetak struk**.

### Melihat Laporan (Owner)

1.  Masuk ke menu **Laba & Rugi**.
2.  Pilih rentang tanggal (Mulai - Akhir).
3.  Klik **Terapkan Filter** untuk melihat kalkulasi profit.
4.  Klik **Export PDF** untuk mengunduh laporan.

## 🤝 Kontribusi

Kontribusi untuk pengembangan fitur baru atau perbaikan bug sangat diterima\!

1.  Fork repositori ini.
2.  Buat branch fitur (`git checkout -b fitur-baru`).
3.  Komit perubahan (`git commit -m 'Menambahkan fitur diskon'`).
4.  Push ke branch (`git push origin fitur-baru`).
5.  Buat Pull Request.

## 📄 Lisensi

Proyek ini didistribusikan di bawah lisensi MIT.

```text
MIT License

Copyright (c) 2025 Toko Z&Z

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
```