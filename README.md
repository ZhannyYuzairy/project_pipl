# Project PIPL

Proyek aplikasi web berbasis CodeIgniter 4 dengan fitur lengkap untuk pemrosesan gambar, manajemen database, dan integrasi layanan web.

## 📋 Daftar Isi

- [Fitur](#fitur)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Prasyarat](#prasyarat)
- [Instalasi](#instalasi)
- [Struktur Proyek](#struktur-proyek)
- [Penggunaan](#penggunaan)
- [Kontribusi](#kontribusi)
- [Lisensi](#lisensi)

## ✨ Fitur

- **Framework CodeIgniter 4**: Framework PHP modern dengan arsitektur MVC
- **Pemrosesan Gambar**: Fungsionalitas kamera dan unggah gambar
- **API RESTful**: Sistem routing bawaan untuk endpoint API
- **Manajemen Database**: Model database terintegrasi dan migrasi
- **Autentikasi & Filter**: Filter keamanan dan penanganan permintaan
- **Lokalisasi**: Dukungan multi-bahasa melalui file Language
- **Pengujian**: Integrasi PHPUnit untuk unit testing

## 🛠 Teknologi yang Digunakan

- **Framework**: CodeIgniter 4
- **Bahasa Pemrograman**: PHP 7.4+
- **Manajer Paket**: Composer
- **Pengujian**: PHPUnit
- **Server Web**: Apache (dengan dukungan .htaccess)
- **Database**: MySQL/MariaDB (dapat dikonfigurasi)
- **Frontend**: HTML5, JavaScript, CSS3

## 📋 Prasyarat

Sebelum memulai, pastikan Anda telah menginstal:

- PHP 7.4 atau lebih tinggi
- Composer
- MySQL/MariaDB 5.7 atau lebih tinggi
- Apache web server dengan mod_rewrite diaktifkan
- Node.js (opsional, untuk tooling frontend)

## 🚀 Instalasi

1. **Clone repositori**
   ```bash
   git clone https://github.com/username-anda/project_pipl.git
   cd project_pipl
   ```

2. **Instal dependensi**
   ```bash
   composer install
   ```

3. **Konfigurasi lingkungan**
   ```bash
   cp .env.example .env
   ```
   Edit `.env` dan konfigurasi kredensial database Anda:
   ```
   database.default.hostname = localhost
   database.default.database = project_pipl
   database.default.username = root
   database.default.password = password_anda
   ```

4. **Jalankan migrasi database**
   ```bash
   php spark migrate
   ```

5. **Atur izin file**
   ```bash
   chmod -R 755 writable/
   chmod -R 755 public/uploads/
   ```

6. **Mulai server pengembangan**
   ```bash
   php spark serve
   ```
   Akses aplikasi di `http://localhost:8080`

## 📁 Struktur Proyek

```
project_pipl/
├── app/                     # Kode aplikasi
│   ├── Config/              # File konfigurasi
│   ├── Controllers/         # Penanganan permintaan
│   ├── Database/            # Migrasi dan seed
│   ├── Filters/             # Filter permintaan/respons
│   ├── Helpers/             # Fungsi helper
│   ├── Language/            # File lokalisasi
│   ├── Libraries/           # Perpustakaan kustom
│   ├── Models/              # Model database
│   ├── Views/               # Template tampilan
│   ├── Routes.php           # Definisi rute
│   └── Common.php           # Fungsi umum
├── public/                  # Aset publik
│   ├── js/                  # File JavaScript
│   ├── uploads/             # Unggahan pengguna
│   ├── index.php            # Titik masuk
│   └── robots.txt           # File SEO
├── system/                  # File inti CodeIgniter
├── tests/                   # Unit testing
├── writable/                # Direktori yang dapat ditulis
├── composer.json            # Konfigurasi Composer
├── phpunit.xml.dist         # Konfigurasi PHPUnit
├── spark                    # Alat CLI CodeIgniter
└── README.md                # File ini
```

## 💡 Penggunaan

### Menjalankan Aplikasi

```bash
# Server pengembangan
php spark serve

# Build produksi (jika berlaku)
# Konfigurasi web server untuk menunjuk ke direktori public/
```

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan ikuti langkah-langkah berikut:

1. Fork repositori
2. Buat branch fitur Anda (`git checkout -b fitur/fitur-luar-biasa`)
3. Komit perubahan Anda (`git commit -m 'Tambah fitur luar biasa'`)
4. Push ke branch (`git push origin fitur/fitur-luar-biasa`)
5. Buka Pull Request

### Standar Kode

- Ikuti standar kode PHP PSR-12
- Tulis unit test untuk fitur baru
- Pastikan semua test lolos sebelum mengirim PR
- Perbarui README jika diperlukan

## 📄 Lisensi

Proyek ini dilisensikan di bawah Lisensi MIT - lihat file [LICENSE](LICENSE) untuk detail.

```
MIT License

Hak Cipta (c) 2024 Project PIPL Contributors

Dengan ini, izin diberikan secara gratis kepada siapa pun yang mendapatkan
salinan dari perangkat lunak ini dan file dokumentasi terkait (\"Perangkat Lunak\"), 
untuk menangani Perangkat Lunak tanpa batasan, termasuk namun tidak terbatas pada 
hak untuk menggunakan, menyalin, memodifikasi, menggabungkan, menerbitkan, 
mendistribusikan, mensublisensikan, dan/atau menjual salinan Perangkat Lunak.
```