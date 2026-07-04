# ☕ Tepi Kopi

Tepi Kopi adalah aplikasi web berbasis **Laravel** untuk mengelola operasional kedai kopi — mulai dari data produk/menu, transaksi, hingga laporan penjualan. Dibangun dengan Laravel (Blade) di sisi backend/view dan Vite untuk build asset frontend.

> Catatan: Deskripsi fitur di bawah adalah gambaran umum berdasarkan struktur project. Silakan sesuaikan dengan fitur aktual yang sudah kamu implementasikan.

## ✨ Fitur

- Manajemen data menu/produk kopi
- Pencatatan transaksi penjualan
- Laporan penjualan
- Autentikasi pengguna (login/register)
- Tampilan antarmuka menggunakan Blade + Vite

## 🛠️ Tech Stack

- **Backend:** PHP, Laravel
- **Frontend/View:** Blade, Vite
- **Database:** MySQL (dump tersedia di `tepi_kopi.sql`)
- **Package manager:** Composer (PHP), NPM (JS/CSS)

## 📋 Prasyarat

Pastikan sudah terinstall di komputer kamu:

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL / MariaDB
- Git

## 🚀 Instalasi

1. **Clone repository**
   ```bash
   git clone https://github.com/ranggapgrl/tepi_kopi.git
   cd tepi_kopi
   ```

2. **Install dependency PHP**
   ```bash
   composer install
   ```

3. **Install dependency JavaScript**
   ```bash
   npm install
   ```

4. **Salin file environment**
   ```bash
   cp .env.example .env
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Konfigurasi database**

   Buka file `.env` dan sesuaikan kredensial database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306 #Menyesuaikan dengan port yang terpasang
   DB_DATABASE=tepi_kopi
   DB_USERNAME=root
   DB_PASSWORD=
   ```

7. **Import database**

   Buat database baru bernama `tepi_kopi`, lalu import file `tepi_kopi.sql` yang sudah disediakan:
   ```bash
   mysql -u root -p tepi_kopi < tepi_kopi.sql
   ```

   Atau jika ingin menggunakan migration bawaan Laravel:
   ```bash
   php artisan migrate
   ```

8. **Build asset frontend**
   ```bash
   npm run build
   ```
   atau untuk mode development:
   ```bash
   npm run dev
   ```

9. **Jalankan server lokal**
   ```bash
   php artisan serve
   ```

   Aplikasi bisa diakses di `http://127.0.0.1:8000`

## 🧪 Menjalankan Test

```bash
php artisan test
```

## 📂 Struktur Project

```
tepi_kopi/
├── app/            # Logic aplikasi (Models, Controllers, dll)
├── bootstrap/      # File bootstrap Laravel
├── config/         # File konfigurasi
├── database/       # Migration, seeder, factory
├── public/         # Entry point & asset publik
├── resources/      # View (Blade), asset frontend
├── routes/         # Definisi route
├── storage/        # File log, cache, upload
├── tests/          # Unit & feature test
└── tepi_kopi.sql   # Dump database
```

## 🤝 Kontribusi

Kontribusi sangat terbuka! Silakan:

1. Fork repository ini
2. Buat branch baru (`git checkout -b fitur-baru`)
3. Commit perubahan (`git commit -m 'Menambahkan fitur baru'`)
4. Push ke branch (`git push origin fitur-baru`)
5. Buat Pull Request

## 📄 Lisensi

Project ini menggunakan lisensi [MIT](https://opensource.org/licenses/MIT), kecuali dinyatakan lain.

## 👤 Author
- Andika M Ridho
- Andi Adi S
- Haichal R
- M Sandi
- Rangga Pagar A
- Rayyan Nur S K
  
GitHub: [@ranggapgrl](https://github.com/ranggapgrl)
