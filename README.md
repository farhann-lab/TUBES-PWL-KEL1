# KELOMPOK ELCO (Kelompok 1)
### Exclusive Local Coffee Origin

| NIM | Nama |
|-----|------|
| 251402028 | Kevin Rodrigues Pandiangan |
| 251402035 | Rafa Fabian Syahputra |
| 251402057 | Jonathan Mulianta Brema Ginting Suka |
| 251402068 | Muhammad Farhan Praditya Harahap |
| 251402071 | Diaz Prananta Ginting |
| 251402127 | Syamil Mali Uzair |
| 251402139 | Morris Vincent Abbiel Situmorang |

### Mata Kuliah
Pemrograman Web Lanjut (PWL)

---

# ELCO — Exclusive Local Coffee Origin
### Sistem Manajemen F&B untuk Café

ELCO (Exclusive Local Coffee Origin) merupakan sistem manajemen Food & Beverage yang dirancang untuk membantu pengelolaan operasional café secara terpusat. Sistem ini dibuat untuk mendukung pengelolaan data cabang, stok, transaksi kasir, keuangan, menu, promo, serta laporan operasional sehingga seluruh aktivitas bisnis café dapat tercatat secara digital dan terintegrasi.

Sistem ELCO memiliki tiga role utama yaitu manager pusat, admin cabang, dan kasir, dimana setiap role memiliki fungsi dan tanggung jawab yang berbeda sesuai kebutuhan operasional.

---

## 👥 Role User Sistem

### 1. Manager Pusat
Manager pusat berfungsi sebagai pengontrol utama seluruh cabang dengan hak akses:
- CRUD stok dan verifikasi permintaan stok dari cabang
- CRUD data cabang
- Mengelola menu, harga dasar, dan promo global
- Melakukan verifikasi keuangan dan laporan cabang

### 2. Admin Cabang
Admin cabang berfokus pada operasional harian cabang, meliputi:
- Memantau stok cabang
- Mengajukan penambahan stok ke pusat
- Mencatat pengeluaran operasional cabang
- Mengajukan kebutuhan alat operasional
- Membuat promo cabang
- Membatalkan transaksi bermasalah
- Mengirim laporan mingguan ke pusat

### 3. Kasir
Kasir bertugas melakukan transaksi penjualan, dengan fitur:
- Memilih menu yang tersedia
- Memproses transaksi pelanggan
- Mengurangi stok otomatis saat transaksi selesai

---

## ⚙️ Fitur Utama Sistem

### ✅ Mengelola Stok
Sistem menyediakan pengelolaan stok per cabang, pengajuan permintaan stok ke pusat, proses verifikasi, serta pencatatan log perubahan stok.

### ✅ Mengelola Cabang
Manager pusat dapat menambah, menghapus, mengaktifkan, maupun menonaktifkan data cabang.

### ✅ Mengelola Keuangan
Sistem mencatat pengeluaran operasional cabang, melakukan verifikasi pusat, serta menghasilkan laporan keuangan.

### ✅ Fitur Kasir
Kasir dapat melakukan pemesanan menu dan sistem akan mengurangi stok bahan secara otomatis ketika transaksi selesai.

### ✅ Laporan Grafik
Sistem menampilkan laporan pemasukan, pengeluaran, dan stok dalam bentuk grafik untuk mempermudah monitoring.

### ✅ Mengelola Menu & Harga
Manager pusat dapat melakukan CRUD menu, menentukan harga global, serta mengatur promo global maupun promo cabang.

---

##  Aturan Bisnis Awal Sistem

- Sistem memiliki tiga role pengguna: manager pusat, admin cabang, dan kasir.
- Cabang hanya dapat dibuat dan diaktifkan oleh manager pusat.
- Stok dikelola per cabang melalui proses request dan verifikasi pusat.
- Menu dan harga dasar hanya dapat dikelola oleh manager pusat.
- Promo global dibuat oleh pusat dan promo cabang memiliki batas tertentu.
- Kasir tidak dapat mengubah transaksi completed, sedangkan pembatalan transaksi dilakukan oleh admin cabang.

---

#  Revisi dan Penyesuaian Sistem Berdasarkan Masukan Dosen

### 1. Fitur Manager Pusat
Pada rancangan awal, manager pusat memiliki fitur laporan stok pusat. Setelah revisi, fitur tersebut dihapus karena laporan dari cabang bisa saja tidak diajukan ketika stok pusat sedang kosong. Manager pusat difokuskan pada verifikasi pengajuan cabang dan laporan operasional.

### 2. Fitur Admin Cabang
Pada rancangan awal, pengajuan stok dan pengajuan alat operasional dibuat dalam halaman terpisah. Setelah revisi, kedua pengajuan tersebut digabungkan menjadi satu menu pengajuan kebutuhan operasional agar proses pengajuan lebih sederhana dan efisien.

### 3. Fitur Kasir dan Pembatalan Transaksi
Pembatalan transaksi tidak dapat dilakukan untuk semua jenis transaksi. Sistem membatasi bahwa pembatalan hanya dapat dilakukan apabila pesanan belum masuk tahap pengolahan. Jika bahan sudah diolah atau pesanan sudah diproses, maka transaksi tidak dapat dibatalkan karena dapat menimbulkan kerugian operasional.

### 4. Perubahan Periode Laporan
Pada rancangan awal, laporan dilakukan secara mingguan dan bulanan. Setelah revisi, laporan disederhanakan menjadi laporan bulanan saja agar monitoring lebih ringkas dan tidak membebani admin cabang.

---

## 🎯 Hasil Akhir Pengembangan

Dengan adanya revisi tersebut, sistem ELCO menjadi lebih sederhana dalam penggunaan, lebih realistis terhadap kondisi operasional café, meminimalisir fitur yang kurang dibutuhkan, serta menghasilkan alur bisnis yang lebih efisien antara pusat dan cabang.

Sistem ini diharapkan mampu menjadi solusi digital untuk manajemen café multi cabang yang terintegrasi.
