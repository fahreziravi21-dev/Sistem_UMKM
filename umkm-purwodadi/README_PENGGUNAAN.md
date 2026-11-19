# Sistem Informasi Pemasaran UMKM Dusun 01 Purwodadi
**Kecamatan Trimurjo, Kabupaten Lampung Tengah**
*Website Pemasaran & Pemesanan Online Berbasis PHP & Framework Laravel 8*

---

## 🚀 Cara Menjalankan Aplikasi

1. **Pastikan MySQL di XAMPP Aktif**
   Pastikan modul MySQL pada XAMPP Control Panel sudah dalam status **Running**.

2. **Buka Terminal di Direktori Proyek**
   ```bash
   cd c:\Project\Sistem_informasi_pemasaran\umkm-purwodadi
   ```

3. **Jalankan Development Server Laravel**
   ```bash
   php artisan serve
   ```

4. **Buka di Browser**
   Buka URL: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 🔑 Akun Bawaan (Default Credentials)

Sistem sudah di-seeding dengan 2 akun uji coba:

### 1. Akun Administrator UMKM
- **URL Login:** [http://127.0.0.1:8000/login](http://127.0.0.1:8000/login)
- **Username:** `admin`
- **Password:** `admin123`
- **Hak Akses:** Masuk ke Dashboard Admin (`/admin`), kelola master produk camilan, verifikasi bukti transfer pembayaran, kelola status pengiriman pesanan, edit info kontak & rekening toko, serta cetak laporan penjualan.

### 2. Akun Pelanggan / Pembeli
- **Username:** `pelanggan1`
- **Password:** `user123`
- **Hak Akses:** Melihat katalog produk camilan, pencarian cepat real-time via AJAX, belanja multi-item ke keranjang, checkout pesanan, bayar otomatis via Payment Gateway atau transfer manual, upload bukti bayar, dan cek riwayat pesanan.
- *(Pengguna baru juga dapat mendaftar langsung melalui menu **Daftar**).*

---

## ⚡ Fitur Baru: Payment Gateway & Pencarian AJAX

### 1. Payment Gateway (Midtrans Snap & Sandbox Simulator)
Sistem kini memiliki 2 jalur pembayaran fleksibel:
- **Opsi A: Payment Gateway Otomatis (Midtrans Snap)**
  - Mendukung pembayaran digital instan: **QRIS** (GoPay, ShopeePay, Dana, OVO), **Virtual Account** (BCA, BRI BRIVA, Mandiri Livin', BNI, Permata), dan gerai retail.
  - Menggunakan Midtrans Snap API yang memunculkan pop-up transaksi langsung di halaman invoice pelanggan.
  - Dilengkapi fitur **⚡ Demo Cepat: Simulasi Pembayaran Sukses (Sandbox)** sehingga saat presentasi atau sidang skripsi, penguji dapat melihat proses verifikasi otomatis secara instan tanpa perlu transfer uang nyata.
  - Mendukung endpoint webhook notification (`/payment-gateway/notification`) dengan validasi signature SHA512.
- **Opsi B: Transfer Manual (Upload Struk)**
  - Tetap dipertahankan sesuai kebutuhan skripsi asli: pelanggan transfer ke rekening Bank BRI milik UMKM dan mengunggah foto struk untuk diverifikasi admin di dashboard.

#### Konfigurasi Midtrans di `.env`:
```env
MIDTRANS_SERVER_KEY=SB-Mid-server-DEMO123456789
MIDTRANS_CLIENT_KEY=SB-Mid-client-DEMO123456789
MIDTRANS_IS_PRODUCTION=false
```
*(Ganti nilai di atas dengan kredensial dari akun [Midtrans Dashboard](https://dashboard.midtrans.com) Anda jika ingin menggunakan sandbox/production nyata).*

---

### 2. Pencarian Real-Time Menggunakan AJAX (Live Search)
- **Pada Katalog Produk Pelanggan (`/katalog`):**
  - Menggunakan teknik **Debounce (250ms)**: saat pembeli mengetik nama camilan (misal: "kelanting", "singkong", "untir"), produk langsung terfilter seketika tanpa me-refresh halaman (*zero full-page reload*).
  - Dilengkapi indikator spinner animasi saat pencarian sedang berlangsung.
  - Filter kategori (*Semua*, *Tersedia*, *Habis*) dan pengurutan (*Termurah*, *Tertinggi*, *Stok Terbanyak*) bekerja secara dinamis melalui AJAX endpoint `/katalog/ajax-search`.
- **Pada Panel Admin Kelola Produk (`/admin/produk`):**
  - Admin dapat mengetik nama produk di kolom pencarian, dan baris tabel produk akan otomatis ter-update via AJAX seketika.

---

## 📦 Struktur Basis Data Ternormalisasi (3NF)

1. **`penggunas`**: Data akun pengguna, password (bcrypt), hak akses multi-level (`admin` / `pembeli`).
2. **`produks`**: Data produk camilan (*Kelanting, Untir-untir, Keripik Singkong*), harga, stok, deskripsi, foto, dan status ketersediaan.
3. **`bookings`**: Header transaksi pemesanan dengan nomor unik invoice (`BK-YYYYMMDD...`), data penerima kiriman, status pesanan (`menunggu_bayar`, `dibayar`, `dikirim`, `selesai`, `batal`), `snap_token`, dan `metode_pembayaran`.
4. **`detail_bookings`**: Normalisasi item rincian pesanan (*multi-item shopping cart*) menghubungkan booking dan aneka produk beserta kuantitas & harga satuan.
5. **`pembayarans`**: Transaksi pembayaran khusus, nomor & bank pengirim, nominal transfer, `transaksi_id`, `metode`, status verifikasi admin (`menunggu`, `terverifikasi`, `ditolak`), dan catatan admin.
6. **`infowebs`**: Pengaturan profil UMKM Purwodadi, nomor WhatsApp, email, alamat fisik toko, dan nomor rekening resmi penerima pembayaran.

---

## 🛠️ Fitur Lengkap Sistem

### A. Antarmuka Publik (Pelanggan)
- **Beranda (Home):** Hero banner UMKM, produk unggulan lokal, profil, alur pemesanan, dan kontak WhatsApp.
- **Katalog Produk:** Pencarian live AJAX, filter ketersediaan stok, sorting harga/stok, badge harga & stok, serta detail info produk.
- **Keranjang Belanja (Multi-item Cart):** Tambah beberapa jenis camilan sekaligus, ubah kuantitas, hitung subtotal dinamis, dan validasi batas stok.
- **Checkout:** Input nama penerima, kontak WhatsApp, dan alamat tujuan pengiriman.
- **Invoice & Tagihan:** Pilihan metode pembayaran (Gateway Otomatis vs Transfer Manual).
- **Lacak Pesanan (Tracking):** Cek status perjalanan pesanan berdasarkan kode booking dengan timeline visual progress.
- **Riwayat Pesanan Saya:** Rekap semua pesanan akun yang sedang login.

### B. Antarmuka Administrator (Admin Panel)
- **Dashboard:** Kartu statistik total omset pendapatan, pesanan baru, produk stok habis, tabel pesanan terbaru, dan peringatan stok menipis.
- **Kelola Produk:** Live AJAX search tabel produk, tambah produk baru, edit harga/stok, ganti gambar, dan hapus produk.
- **Kelola Pesanan (Booking):** Filter status pesanan (*Menunggu Bayar, Dibayar, Dikirim, Selesai, Batal*), lihat rincian pesanan, dan perbarui status kirim. Jika pesanan dibatalkan, sistem otomatis mengembalikan stok barang.
- **Verifikasi Pembayaran:** Tinjau bukti transfer pembeli atau log transaksi Payment Gateway, tombol *Terverifikasi* (otomatis mengubah status pesanan ke *Dibayar*), atau *Tolak* disertai catatan evaluasi.
- **Pengaturan Web & Rekening:** Edit informasi toko, alamat di Trimurjo, email, dan rekening bank UMKM.
- **Laporan Penjualan:** Filter rentang tanggal transaksi yang telah selesai (*status: selesai*), kalkulasi omset, serta fitur **Cetak Laporan / PDF** berformat resmi yang dilengkapi kolom tanda tangan pimpinan usaha (sangat cocok untuk lampiran skripsi).
