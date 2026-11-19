-- TiDB Cloud & MySQL Compatible Dump
-- Database: `db_pemasaran_umkm`
-- UMKM Dusun 01 Purwodadi, Trimurjo, Lampung Tengah

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `db_pemasaran_umkm` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_pemasaran_umkm`;

-- --------------------------------------------------------
-- Table structure for table `penggunas`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `penggunas`;
CREATE TABLE `penggunas` (
  `id_pengguna` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_pengguna` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_tlp` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `level` enum('admin','pembeli') NOT NULL DEFAULT 'pembeli',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_pengguna`),
  UNIQUE KEY `penggunas_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `penggunas` (`id_pengguna`, `nama_pengguna`, `username`, `password`, `no_tlp`, `alamat`, `level`, `created_at`, `updated_at`) VALUES
(1, 'Administrator UMKM', 'admin', '$2y$10$mWTGrwVZjb8s/OD1CTyxOOhGnzz4OeYwAVFWkjBGbc66ElASWQNAa', '081234567890', 'Dusun 01 Purwodadi, Trimurjo, Lampung Tengah', 'admin', '2026-11-05 01:56:33', '2026-11-05 01:56:33'),
(2, 'Siti Aminah', 'pelanggan1', '$2y$10$wU./lozxZ5He75wcgnh34OEvJTPnJNRr3cLjwA6ev.cv..M4QZmDa', '085712345678', 'Jl. Sudirman No. 12, Metro', 'pembeli', '2026-11-05 01:56:33', '2026-11-05 01:56:33');

-- --------------------------------------------------------
-- Table structure for table `produks`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `produks`;
CREATE TABLE `produks` (
  `id_produk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `merk` varchar(100) NOT NULL,
  `harga` bigint(20) UNSIGNED NOT NULL,
  `stok` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `deskripsi` text DEFAULT NULL,
  `status` enum('tersedia','habis') NOT NULL DEFAULT 'tersedia',
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_produk`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `produks` (`id_produk`, `merk`, `harga`, `stok`, `deskripsi`, `status`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 'Kelanting Bumbu Bawang Gurih', 15000, 60, 'Kelanting gurih renyah khas Dusun 01 Purwodadi dengan racikan rempah bawang putih pilihan. Tekstur garing renyah dan tidak keras.', 'tersedia', 'prod_1794528288_6af65420b545e.png', '2026-11-12 01:22:51', '2026-11-12 17:04:48'),
(2, 'Kelanting Pedas Manis Balado', 16000, 45, 'Camilan kelanting tradisional dengan baluran bumbu balado pedas manis gurih yang bikin ketagihan.', 'tersedia', 'prod_1794528221_6af653ddd197e.jpeg', '2026-11-12 01:22:51', '2026-11-12 17:03:41'),
(3, 'Kelanting Jagung Manis Renyah', 15000, 35, 'Kelanting renyah berpadu bumbu jagung manis bakar gurih, favorit semua usia untuk teman santai.', 'tersedia', 'prod_1794528112_6af653700d51b.png', '2026-11-12 01:22:51', '2026-11-12 17:01:52'),
(4, 'Kelanting Manis', 15000, 41, 'Kelanting khas dengan baluran karamel gula aren murni khas Trimurjo Lampung Tengah. Manis legit dan gurih.', 'tersedia', 'prod_1794527947_6af652cb2f6fd.jpg', '2026-11-12 01:22:51', '2026-11-12 17:07:43'),
(5, 'Untir-Untir Manis Wijen Renyah', 12000, 50, 'Kue tambang untir-untir goreng tradisional bertekstur renyah dengan taburan biji wijen wangi dan rasa manis pas.', 'tersedia', 'prod_1794527858_6af6527214977.jpg', '2026-11-12 01:22:51', '2026-11-12 16:57:38'),
(6, 'Untir-Untir Gurih Asin Bawang', 12000, 40, 'Kue untir-untir varian asin gurih dengan aroma daun bawang segar, empuk renyah tanpa keras.', 'tersedia', 'prod_1794527628_6af6518ca4a8a.jpg', '2026-11-12 01:22:51', '2026-11-12 16:53:48'),
(7, 'Untir-Untir Pandan Wangi', 13000, 30, 'Kue untir-untir unik beraroma ekstrak daun pandan asli, harum wangi dan manis legit menggugah selera.', 'tersedia', 'prod_1794527578_6af6515a4a00f.jpg', '2026-11-12 01:22:51', '2026-11-12 16:52:58'),
(8, 'Keripik Singkong Original Bawang', 10000, 80, 'Keripik singkong renyah hasil panen singkong lokal Dusun 01 Purwodadi, digoreng tipis renyah bumbu gurih alami.', 'tersedia', 'prod_1794527451_6af650db7d4e2.jpg', '2026-11-12 01:22:51', '2026-11-12 16:50:51'),
(9, 'Keripik Singkong Balado Pedas', 12000, 54, 'Olahan keripik singkong dengan bumbu cabai merah pedas manis mantap khas Lampung Tengah.', 'tersedia', 'prod_1794527301_6af6504542504.jpg', '2026-11-12 01:22:51', '2026-11-12 18:29:44'),
(10, 'Keripik Singkong Keju Gurih', 13000, 40, 'Keripik singkong renyah dengan taburan bubuk keju premium yang gurih creamy dan lezat.', 'tersedia', 'prod_1794527141_6af64fa5dd0a3.jpg', '2026-11-12 01:22:51', '2026-11-12 16:45:41'),
(11, 'Keripik Pisang Cokelat Khas Lampung', 18000, 65, 'Oleh-oleh legendaris khas Lampung dari pisang kepok pilihan dengan lumuran bubuk cokelat tebal dan lumer di mulut.', 'tersedia', 'prod_1794527045_6af64f45f4137.jpg', '2026-11-12 01:22:51', '2026-11-12 16:44:06'),
(12, 'Keripik Pisang Keju Susu', 18000, 50, 'Keripik pisang khas Lampung dengan balutan keju manis gurih dan susu yang creamy renyah.', 'tersedia', 'prod_1794526992_6af64f100a464.jpg', '2026-11-12 01:22:51', '2026-11-12 16:43:12'),
(13, 'Stik Bawang Gurih Renyah', 12000, 45, 'Stik bawang renyah empuk dengan wangi irisan daun seledri segar dan rempah bawang gurih.', 'tersedia', 'prod_1794526927_6af64ecf25b17.jpg', '2026-11-12 01:22:51', '2026-11-12 16:42:07'),
(14, 'Peyek Kacang Tanah Daun Jeruk', 14000, 35, 'Rempeyek kacang tanah melimpah dengan aroma harum daun jeruk wangi, super renyah dan gurih.', 'tersedia', 'prod_1794526869_6af64e9593ba8.jpg', '2026-11-12 01:22:51', '2026-11-12 16:41:09'),
(15, 'Kembang Goyang Manis Wijen', 13000, 30, 'Kue tradisional kembang goyang berbentuk bunga cantik yang renyah manis gurih bertabur wijen.', 'tersedia', 'prod_1794526717_6af64dfda2c2f.jpg', '2026-11-12 01:22:51', '2026-11-12 16:38:37');

-- --------------------------------------------------------
-- Table structure for table `bookings`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `bookings`;
CREATE TABLE `bookings` (
  `id_booking` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_booking` varchar(30) NOT NULL,
  `id_pengguna` bigint(20) UNSIGNED NOT NULL,
  `nama_penerima` varchar(100) NOT NULL,
  `no_tlp_penerima` varchar(15) NOT NULL,
  `alamat_pengiriman` text NOT NULL,
  `total_harga` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `status_booking` enum('menunggu_bayar','dibayar','dikirim','selesai','batal') NOT NULL DEFAULT 'menunggu_bayar',
  `snap_token` varchar(255) DEFAULT NULL,
  `metode_pembayaran` enum('transfer_manual','payment_gateway') NOT NULL DEFAULT 'transfer_manual',
  `tgl_booking` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_booking`),
  UNIQUE KEY `bookings_kode_booking_unique` (`kode_booking`),
  KEY `bookings_id_pengguna_foreign` (`id_pengguna`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `bookings` (`id_booking`, `kode_booking`, `id_pengguna`, `nama_penerima`, `no_tlp_penerima`, `alamat_pengiriman`, `total_harga`, `status_booking`, `snap_token`, `metode_pembayaran`, `tgl_booking`) VALUES
(1, 'BK-20261105090511', 2, 'Siti Aminah', '085712345678', 'Jl. Sudirman No. 12, Metro', 12000, 'batal', 'DEMO-SNAP-3BEE0BB6ABAD73F16972B824', 'payment_gateway', '2026-11-05 09:05:11'),
(2, 'BK-20261105160439', 2, 'Siti Aminah', '085712345678', 'Jl. Sudirman No. 12, Metro', 24000, 'dibayar', 'DEMO-SNAP-BC5818D4EAFA4117645F0284', 'payment_gateway', '2026-11-05 16:04:39'),
(3, 'BK-20261106025007', 2, 'Siti Aminah', '085712345678', 'Jl. Sudirman No. 12, Metro', 27000, 'dibayar', 'DEMO-SNAP-0139C452C53A82A7670C4EDD', 'payment_gateway', '2026-11-06 02:50:07'),
(4, 'BK-20261112073950', 2, 'Siti Aminah', '085712345678', 'Jl. Sudirman No. 12, Metro', 12000, 'dibayar', NULL, 'payment_gateway', '2026-11-12 07:39:50'),
(5, 'BK-20261112074616', 2, 'Siti Aminah', '085712345678', 'Jl. Sudirman No. 12, Metro', 12000, 'dibayar', NULL, 'payment_gateway', '2026-11-12 07:46:16'),
(6, 'BK-20261112075301', 2, 'Siti Aminah', '085712345678', 'Jl. Sudirman No. 12, Metro', 12000, 'dibayar', NULL, 'payment_gateway', '2026-11-12 07:53:01'),
(7, 'BK-20261113012944', 1, 'Administrator UMKM', '081234567890', 'Dusun 01 Purwodadi, Trimurjo, Lampung Tengah', 12000, 'menunggu_bayar', 'DEMO-SNAP-10608F0AD489FD00F003D120', 'payment_gateway', '2026-11-13 01:29:44');

-- --------------------------------------------------------
-- Table structure for table `detail_bookings`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `detail_bookings`;
CREATE TABLE `detail_bookings` (
  `id_detail` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_booking` bigint(20) UNSIGNED NOT NULL,
  `id_produk` bigint(20) UNSIGNED NOT NULL,
  `harga_satuan` bigint(20) UNSIGNED NOT NULL,
  `jumlah` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `subtotal` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_detail`),
  KEY `detail_bookings_id_booking_foreign` (`id_booking`),
  KEY `detail_bookings_id_produk_foreign` (`id_produk`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `detail_bookings` (`id_detail`, `id_booking`, `id_produk`, `harga_satuan`, `jumlah`, `subtotal`) VALUES
(1, 1, 4, 12000, 1, 12000),
(2, 2, 4, 12000, 2, 24000),
(3, 3, 1, 15000, 1, 15000),
(4, 3, 2, 12000, 1, 12000),
(5, 4, 4, 12000, 1, 12000),
(6, 5, 4, 12000, 1, 12000),
(7, 6, 4, 12000, 1, 12000),
(8, 7, 9, 12000, 1, 12000);

-- --------------------------------------------------------
-- Table structure for table `pembayarans`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `pembayarans`;
CREATE TABLE `pembayarans` (
  `id_pembayaran` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_booking` bigint(20) UNSIGNED NOT NULL,
  `transaksi_id` varchar(100) DEFAULT NULL,
  `metode` varchar(50) NOT NULL DEFAULT 'manual',
  `no_rekening` varchar(30) DEFAULT NULL,
  `nama_rekening` varchar(100) DEFAULT NULL,
  `bank_pengirim` varchar(50) DEFAULT NULL,
  `nominal` bigint(20) UNSIGNED NOT NULL,
  `tgl_pembayaran` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bukti` varchar(255) DEFAULT NULL,
  `status_konfirmasi` enum('menunggu','terverifikasi','ditolak') NOT NULL DEFAULT 'menunggu',
  `catatan_admin` text DEFAULT NULL,
  PRIMARY KEY (`id_pembayaran`),
  UNIQUE KEY `pembayarans_id_booking_unique` (`id_booking`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pembayarans` (`id_pembayaran`, `id_booking`, `transaksi_id`, `metode`, `no_rekening`, `nama_rekening`, `bank_pengirim`, `nominal`, `tgl_pembayaran`, `bukti`, `status_konfirmasi`, `catatan_admin`) VALUES
(1, 2, 'MIDTRANS-SIM-6AECA926D6332', 'payment_gateway_qris', 'VA-GATEWAY-509910', 'Siti Aminah', 'Midtrans (QRIS)', 24000, '2026-11-05 16:04:54', 'midtrans_gateway_verified.png', 'terverifikasi', 'Pembayaran otomatis terverifikasi melalui Payment Gateway (QRIS)'),
(2, 3, 'MIDTRANS-SIM-6AED406F95639', 'payment_gateway_qris', 'VA-GATEWAY-334166', 'Siti Aminah', 'Midtrans (QRIS)', 27000, '2026-11-06 02:50:23', 'midtrans_gateway_verified.png', 'terverifikasi', 'Pembayaran otomatis terverifikasi melalui Payment Gateway (QRIS)'),
(3, 4, 'QRIS-6AF56E55AF146', 'qris_buatqris', 'QRIS-NATIONAL-ID', 'Siti Aminah', 'QRIS (buatqris.site / GoPay / OVO / DANA)', 12000, '2026-11-12 07:44:21', 'qris_payment_verified.png', 'terverifikasi', 'Pembayaran otomatis terverifikasi via QRIS Dinamis (buatqris.site)'),
(4, 5, 'QRIS-6AF56FBDCBE78', 'qris_buatqris', 'QRIS-NATIONAL-ID', 'Siti Aminah', 'QRIS (buatqris.site / GoPay / OVO / DANA)', 12000, '2026-11-12 07:50:21', 'qris_payment_verified.png', 'terverifikasi', 'Pembayaran otomatis terverifikasi via QRIS Dinamis (buatqris.site)'),
(5, 6, 'QRIS-6AF57131634EF', 'qris_buatqris', 'QRIS-NATIONAL-ID', 'Siti Aminah', 'QRIS (buatqris.site / GoPay / OVO / DANA)', 12000, '2026-11-12 07:56:33', 'qris_payment_verified.png', 'terverifikasi', 'Pembayaran otomatis terverifikasi via QRIS Dinamis (buatqris.site)');

-- --------------------------------------------------------
-- Table structure for table `infowebs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `infowebs`;
CREATE TABLE `infowebs` (
  `id_infoweb` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_web` varchar(100) NOT NULL DEFAULT 'UMKM Dusun 01 Purwodadi',
  `telp` varchar(15) NOT NULL,
  `alamat` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `bank_toko` varchar(50) NOT NULL DEFAULT 'Bank BRI',
  `no_rek_toko` varchar(50) NOT NULL,
  `atas_nama_toko` varchar(100) NOT NULL,
  `qris_statis` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_infoweb`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `infowebs` (`id_infoweb`, `nama_web`, `telp`, `alamat`, `email`, `bank_toko`, `no_rek_toko`, `atas_nama_toko`, `qris_statis`, `created_at`, `updated_at`) VALUES
(1, 'UMKM Dusun 01 Purwodadi', '081377889900', 'Dusun 01 Purwodadi, Kecamatan Trimurjo, Kabupaten Lampung Tengah, Provinsi Lampung 34172', 'umkmpurwodadi@gmail.com', 'Dana', '085709696451', 'UMKM Purwodadi', '00020101021126570011ID.DANA.WWW011893600915303528334202090352833420303UMI51440014ID.CO.QRIS.WWW0215ID10265991689230303UMI5204561153033605802ID5912Julyan Store6015KABUPATEN LAMPU61053438663043C8F', '2026-11-05 01:56:33', '2026-11-12 00:52:33');

-- --------------------------------------------------------
-- Table structure for table `migrations`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_01_01_000001_create_penggunas_table', 1),
(6, '2024_01_01_000002_create_produks_table', 1),
(7, '2024_01_01_000003_create_bookings_table', 1),
(8, '2024_01_01_000004_create_detail_bookings_table', 1),
(9, '2024_01_01_000005_create_pembayarans_table', 1),
(10, '2024_01_01_000006_create_infowebs_table', 1),
(11, '2026_11_05_103250_add_payment_gateway_columns_to_bookings_and_pembayarans', 2),
(12, '2026_11_12_130100_add_qris_statis_to_infowebs_table', 3);

-- --------------------------------------------------------
-- Table structure for table `password_resets`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `failed_jobs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `personal_access_tokens`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Foreign Keys
-- --------------------------------------------------------
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_id_pengguna_foreign` FOREIGN KEY (`id_pengguna`) REFERENCES `penggunas` (`id_pengguna`) ON UPDATE CASCADE;

ALTER TABLE `detail_bookings`
  ADD CONSTRAINT `detail_bookings_id_booking_foreign` FOREIGN KEY (`id_booking`) REFERENCES `bookings` (`id_booking`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_bookings_id_produk_foreign` FOREIGN KEY (`id_produk`) REFERENCES `produks` (`id_produk`) ON UPDATE CASCADE;

ALTER TABLE `pembayarans`
  ADD CONSTRAINT `pembayarans_id_booking_foreign` FOREIGN KEY (`id_booking`) REFERENCES `bookings` (`id_booking`) ON DELETE CASCADE ON UPDATE CASCADE;

SET FOREIGN_KEY_CHECKS = 1;
