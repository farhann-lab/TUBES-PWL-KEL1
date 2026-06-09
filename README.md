# KELOMPOK ELCO (Kelompok 1)
## Exclusive Local Coffee Origin

| NIM | Nama |
| --- | --- |
| 251402028 | Kevin Rodrigues Pandiangan |
| 251402035 | Rafa Fabian Syahputra |
| 251402057 | Jonathan Mulianta Brema Ginting Suka |
| 251402068 | Muhammad Farhan Praditya Harahap |
| 251402071 | Diaz Prananta Ginting |
| 251402127 | Syamil Mali Uzair |
| 251402139 | Morris Vincent Abbiel Situmorang |

**Mata Kuliah:** Pemrograman Web Lanjut (PWL)

---

# ELCO - Exclusive Local Coffee Origin
## Sistem Manajemen F&B untuk Cafe

ELCO adalah aplikasi web manajemen Food & Beverage untuk operasional cafe multi cabang. Sistem ini membantu pengelolaan data cabang, akun pengguna, menu, bahan baku, stok cabang, transaksi kasir, promo, pengeluaran, verifikasi operasional, dan laporan keuangan secara terpusat.

Aplikasi dibangun dengan Laravel dan menerapkan pembagian akses berdasarkan tiga role utama: Manager Pusat, Admin Cabang, dan Kasir.

---

## Teknologi yang Digunakan

- Laravel 13
- PHP 8.3
- Laravel Breeze Authentication
- MySQL / database relasional Laravel
- Blade Template
- Tailwind CSS
- Vite
- Alpine.js
- SweetAlert2
- Phosphor Icons

---

## Role User Sistem

### 1. Manager Pusat

Manager Pusat berperan sebagai pengelola utama seluruh cabang ELCO.

Fitur Manager Pusat:

- Dashboard ringkasan cabang, menu aktif, request pending, pemasukan, pengeluaran, grafik tahunan, aktivitas cabang, dan promo aktif.
- CRUD data cabang beserta pembuatan akun admin cabang.
- Aktivasi, nonaktifkan, restore, dan hapus permanen cabang.
- CRUD menu dan harga dasar.
- Upload gambar menu dan fallback gambar otomatis berdasarkan kategori/nama menu.
- Kelola master bahan baku.
- Kelola resep bahan baku untuk menu minuman.
- Distribusi menu ke seluruh cabang aktif.
- Verifikasi pengajuan kebutuhan dari admin cabang.
- Konfirmasi final pengiriman barang sebelum stok cabang bertambah.
- Verifikasi atau tolak pengeluaran operasional cabang.
- Membuat dan mengelola promo global.
- Review promo cabang, termasuk approve dan reject.
- Melihat transaksi seluruh cabang.
- Melihat laporan pemasukan, pengeluaran, profit, performa cabang, dan export laporan CSV.

### 2. Admin Cabang

Admin Cabang berperan mengelola operasional harian pada cabang masing-masing.

Fitur Admin Cabang:

- Dashboard ringkasan stok, stok menipis, pengajuan pending, pemasukan, pengeluaran, dan transaksi terbaru.
- Monitoring stok menu dan stok bahan baku pada cabang.
- Mengajukan kebutuhan stok bahan baku, produk jadi, atau kebutuhan operasional.
- Konfirmasi barang datang dengan catatan dan foto pengiriman.
- Mencatat pengeluaran operasional cabang beserta bukti struk.
- Menghapus pengeluaran yang masih berstatus pending.
- Membuat, mengedit, dan menghapus promo cabang.
- Promo cabang wajib melalui review Manager Pusat sebelum aktif.
- Melihat promo global yang aktif.
- Melihat transaksi cabang per bulan.
- Menyetujui atau menolak permintaan pembatalan transaksi dari kasir.
- Membuat akun kasir cabang dengan email domain `@elco.com`.
- Melihat laporan bulanan cabang, grafik pemasukan/pengeluaran, kategori pengeluaran, stok kritis, dan export laporan XLSX.

### 3. Kasir

Kasir berperan sebagai pengguna POS untuk transaksi penjualan pelanggan.

Fitur Kasir:

- Dashboard transaksi dan total penjualan harian.
- Halaman POS untuk memilih menu yang tersedia pada cabang.
- Melihat ketersediaan menu berdasarkan stok produk jadi atau jumlah porsi dari bahan baku.
- Memproses transaksi dengan metode pembayaran cash, transfer, atau QRIS.
- Menggunakan promo aktif, baik promo global maupun promo cabang.
- Sistem menghitung subtotal, diskon, total pembayaran, dan nomor invoice otomatis.
- Stok produk jadi berkurang otomatis setelah transaksi selesai.
- Bahan baku berkurang otomatis sesuai resep menu minuman.
- Melihat riwayat transaksi terbaru.
- Mengirim permintaan pembatalan transaksi ke Admin Cabang.

---

## Fitur Utama Sistem

### Manajemen Cabang

Manager Pusat dapat menambah cabang baru, mengatur status cabang, membuat akun admin cabang, menonaktifkan cabang, memulihkan data cabang, dan menghapus cabang beserta akun terkait.

### Manajemen Menu, Harga, dan Bahan Baku

Menu dikelola oleh Manager Pusat. Menu memiliki kategori `minuman`, `makanan`, dan `snack`.

Sistem membedakan jenis stok menu:

- `bahan_baku`: digunakan untuk menu minuman. Stok dihitung dari komposisi resep bahan baku.
- `kuantitas_jadi`: digunakan untuk makanan dan snack. Stok dihitung langsung dalam satuan produk jadi.

Menu minuman wajib memiliki resep bahan baku agar sistem dapat menghitung jumlah porsi tersedia dan mengurangi stok bahan baku saat transaksi berhasil.

### Manajemen Stok Cabang

Stok dikelola per cabang. Admin Cabang dapat mengajukan kebutuhan stok atau operasional ke Manager Pusat.

Alur pengajuan stok:

1. Admin Cabang membuat pengajuan kebutuhan.
2. Manager Pusat menyetujui atau menolak pengajuan.
3. Jika disetujui, Admin Cabang mengonfirmasi barang sudah datang.
4. Manager Pusat melakukan konfirmasi final.
5. Sistem menambahkan stok ke cabang sesuai jenis item.

Stok dapat berupa bahan baku seperti gram/ml/pcs atau produk jadi seperti makanan dan snack.

### Transaksi Kasir

Kasir melakukan transaksi melalui halaman POS. Sistem mengecek stok sebelum transaksi disimpan.

Untuk makanan dan snack, sistem mengecek stok produk jadi di `branch_stocks`.
Untuk minuman, sistem mengecek kebutuhan bahan baku dari resep di `menu_ingredients` dan stok cabang di `ingredient_stocks`.

Jika transaksi berhasil:

- Nomor invoice dibuat otomatis.
- Diskon promo dihitung otomatis.
- Data transaksi dan item transaksi disimpan.
- Stok produk jadi atau bahan baku dikurangi otomatis.

### Pembatalan Transaksi

Kasir tidak dapat langsung membatalkan transaksi selesai. Kasir hanya dapat mengirim permintaan pembatalan dengan alasan.

Admin Cabang dapat menyetujui pembatalan jika transaksi memenuhi syarat. Saat transaksi dibatalkan, sistem mengembalikan stok:

- Produk jadi dikembalikan ke `branch_stocks`.
- Bahan baku minuman dikembalikan ke `ingredient_stocks` sesuai resep.

### Manajemen Promo

Sistem memiliki dua jenis promo:

- Promo global dibuat oleh Manager Pusat dan dapat digunakan di semua cabang.
- Promo cabang dibuat oleh Admin Cabang dan harus disetujui Manager Pusat.

Promo mendukung tipe diskon:

- Persentase
- Nominal tetap

Promo juga memiliki periode aktif, minimum pembelian, status aktif, dan status review.

### Manajemen Pengeluaran

Admin Cabang mencatat pengeluaran cabang berdasarkan kategori operasional, bahan baku, peralatan, gaji, atau lainnya.

Manager Pusat dapat memverifikasi atau menolak pengeluaran. Data pengeluaran yang sudah diverifikasi digunakan dalam laporan keuangan.

### Laporan dan Grafik

Sistem menyediakan laporan berbasis bulan dan tahun.

Laporan Manager Pusat:

- Total pemasukan
- Total pengeluaran
- Profit
- Jumlah transaksi
- Grafik pemasukan dan pengeluaran tahunan
- Performa per cabang
- Riwayat transaksi
- Export CSV

Laporan Admin Cabang:

- Total pemasukan cabang
- Total pengeluaran cabang
- Profit cabang
- Jumlah transaksi
- Grafik pemasukan dan pengeluaran
- Pengeluaran berdasarkan kategori
- Stok kritis
- Export XLSX

---

## Aturan Bisnis Sistem

- Sistem memiliki tiga role utama: Manager Pusat, Admin Cabang, dan Kasir.
- Setiap user diarahkan otomatis ke dashboard sesuai role setelah login.
- Akses halaman dibatasi menggunakan middleware role.
- Akun yang tidak aktif tidak dapat menggunakan sistem.
- Cabang hanya dapat dibuat dan dikelola oleh Manager Pusat.
- Email akun admin dan kasir wajib menggunakan domain `@elco.com`.
- Admin Cabang hanya dapat mengelola data pada cabangnya sendiri.
- Menu, harga dasar, dan master bahan baku hanya dikelola oleh Manager Pusat.
- Menu minuman wajib memiliki resep bahan baku.
- Pengajuan stok harus diverifikasi Manager Pusat.
- Stok cabang baru bertambah setelah Admin Cabang mengonfirmasi barang datang dan Manager Pusat melakukan konfirmasi final.
- Promo cabang harus direview oleh Manager Pusat sebelum aktif.
- Kasir tidak dapat mengubah transaksi yang sudah completed.
- Pembatalan transaksi dilakukan melalui permintaan kasir dan persetujuan Admin Cabang.
- Laporan operasional difokuskan pada periode bulanan.

---

## Struktur Modul

```text
app/
  Http/Controllers/
    Manager/      Modul Manager Pusat
    Admin/        Modul Admin Cabang
    Kasir/        Modul Kasir/POS
  Models/         Model Branch, Menu, Stock, Transaction, Promotion, Expense, dan lainnya

database/
  migrations/     Struktur tabel aplikasi
  seeders/        Data awal user, cabang, bahan baku, menu, resep, dan stok

resources/
  views/
    manager/      Halaman Manager Pusat
    admin/        Halaman Admin Cabang
    kasir/        Halaman Kasir
  css/
  js/

routes/
  web.php         Routing role manager, admin, kasir, auth, dan profile
```

---

## Akun Default Seeder

Setelah menjalankan seeder, aplikasi menyediakan akun awal berikut:

| Role | Email | Password |
| --- | --- | --- |
| Manager Pusat | manager@elco.com | password |
| Admin Cabang | admin1@elco.com | password |
| Kasir | kasir1@elco.com | password |

Seeder juga membuat data awal cabang, menu, bahan baku, resep menu minuman, stok bahan baku, dan stok produk jadi.

---

## Cara Menjalankan Project

### 1. Clone repository

```bash
git clone <url-repository>
cd TUBES-PWL-KEL1
```

### 2. Install dependency backend

```bash
composer install
```

### 3. Install dependency frontend

```bash
npm install
```

### 4. Siapkan file environment

Buat atau sesuaikan file `.env`, lalu atur koneksi database.

Contoh konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=elco
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Jalankan migrasi dan seeder

```bash
php artisan migrate --seed
```

### 7. Buat storage link

```bash
php artisan storage:link
```

### 8. Jalankan aplikasi

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm run dev
```

Aplikasi dapat dibuka melalui:

```text
http://127.0.0.1:8000
```

Alternatif untuk menjalankan server, queue, logs, dan Vite sekaligus:

```bash
composer run dev
```

---

## Testing

Jalankan test Laravel dengan perintah:

```bash
php artisan test
```

Atau melalui script Composer:

```bash
composer test
```

---

## Hasil Akhir Pengembangan

ELCO telah dikembangkan menjadi sistem manajemen cafe multi cabang yang lebih terstruktur dan realistis untuk kebutuhan operasional. Sistem mendukung pemisahan akses per role, manajemen stok berbasis bahan baku dan produk jadi, transaksi kasir dengan pengurangan stok otomatis, review promo cabang, verifikasi pengeluaran, serta laporan bulanan yang dapat digunakan untuk monitoring bisnis.

Dengan alur tersebut, ELCO dapat membantu proses operasional cafe berjalan lebih terpusat, terdokumentasi, dan mudah dipantau oleh Manager Pusat maupun Admin Cabang.
