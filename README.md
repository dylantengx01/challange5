# Bale Laptop - Toko Laptop (PHP native + Tailwind CDN)

## Struktur folder
```
bale-laptop/
├── includes/
│   ├── config.php        # session, zona waktu, nama toko, info kontak, aturan ongkir
│   ├── init.php          # require config + data + fungsi (1 baris di tiap halaman)
│   ├── data.php          # array kategori, 12 produk (laptop + aksesori), voucher, metode bayar
│   ├── fungsi.php        # format rupiah, diskon, cari/filter, keranjang, banding, CSRF, flash
│   ├── header.php        # navbar, pencarian, badge keranjang, tema warna, pesan flash
│   ├── footer.php
│   └── kartu-produk.php  # komponen kartu produk (dipakai ulang)
├── assets/
│   ├── css/style.css
│   └── js/app.js
├── index.php             # beranda
├── produk.php            # katalog + cari/filter kategori, harga, RAM + urutkan
├── detail.php            # detail produk + spesifikasi + produk terkait
├── bandingkan.php        # bandingkan hingga 3 laptop
├── keranjang.php         # keranjang + voucher
├── checkout.php          # form pengiriman + validasi
├── pesanan.php           # nota pesanan terakhir
└── kontak.php            # kontak + form
```

## Menjalankan
Letakkan folder di `htdocs` (XAMPP) lalu buka `http://localhost/bale-laptop/`
atau jalankan `php -S localhost:8000` di dalam folder. Butuh PHP 8.0+ dan internet (Tailwind CDN).

## Voucher untuk dicoba
`BALE5` (5%, maks Rp 500.000) dan `KAMPUS200` (Rp 200.000).
