<?php
// ============================================
// HALAMAN BERANDA
// ============================================
require_once __DIR__ . '/includes/init.php';

$judulHalaman = "Beranda";
$halamanAktif = "index.php";

// Ringkasan toko
$jumlahProduk = count($produk);
$jumlahDiskon = hitungProdukDiskon($produk);
$nilaiStok    = hitungNilaiStok($produk);

// Produk unggulan & diskon terbesar
$unggulan    = array_slice(array_filter($produk, fn($p) => $p["unggulan"]), 0, 4, true);
$diskonBesar = array_slice(cariProduk($produk, "", "", "diskon"), 0, 3, true);

require __DIR__ . '/includes/header.php';
?>

<!-- HERO -->
<section class="hero-bg text-white">
    <div class="max-w-6xl mx-auto px-4 py-16 md:py-24 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <span class="inline-block bg-amber-400/20 text-amber-300 text-xs font-semibold px-3 py-1 rounded-full mb-4">Spesialis laptop di Bali</span>
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-4">Temukan laptop yang <span class="text-amber-400">pas buatmu.</span></h1>
            <p class="text-slate-300 mb-8"><?= e($slogan) ?></p>
            <div class="flex flex-wrap gap-3">
                <a href="produk.php" class="bg-amber-400 hover:bg-amber-300 text-slate-900 font-bold px-6 py-3 rounded-xl">Lihat semua laptop</a>
                <a href="bandingkan.php" class="border border-white/30 hover:bg-white/10 font-semibold px-6 py-3 rounded-xl">⚖️ Bandingkan</a>
            </div>
        </div>
        <div class="text-center text-[8rem] md:text-[10rem] melayang select-none" aria-hidden="true">💻</div>
    </div>
</section>

<div class="max-w-6xl mx-auto px-4 py-10 space-y-14">

    <!-- Ringkasan -->
    <section class="grid sm:grid-cols-3 gap-4 -mt-16 relative">
        <div class="bg-white rounded-2xl shadow-lg p-5">
            <p class="text-sm text-slate-500">Jumlah produk</p>
            <p class="text-2xl font-extrabold"><?= $jumlahProduk ?></p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-5">
            <p class="text-sm text-slate-500">Sedang diskon</p>
            <p class="text-2xl font-extrabold"><?= $jumlahDiskon ?> produk</p>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-5">
            <p class="text-sm text-slate-500">Total nilai stok</p>
            <p class="text-2xl font-extrabold"><?= formatRupiah($nilaiStok) ?></p>
        </div>
    </section>

    <!-- Cari berdasarkan kebutuhan -->
    <section>
        <h2 class="text-2xl font-bold mb-1">Cari sesuai kebutuhanmu</h2>
        <p class="text-slate-500 text-sm mb-4">Pilih kategori, kami tunjukkan laptop yang cocok.</p>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <?php foreach ($kategori as $kunci => $k): ?>
                <a href="produk.php?kategori=<?= $kunci ?>" class="bg-gradient-to-br <?= $k['warna'] ?> text-white rounded-2xl p-5 text-center shadow hover:shadow-xl hover:-translate-y-1 transition">
                    <div class="text-4xl mb-2"><?= $k['ikon'] ?></div>
                    <p class="text-sm font-semibold"><?= e($k['nama']) ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Budget cepat -->
    <section>
        <h2 class="text-2xl font-bold mb-4">Sesuaikan budget</h2>
        <div class="flex flex-wrap gap-3">
            <?php foreach ($opsiHarga as $nilai => $label): ?>
                <a href="produk.php?maks=<?= $nilai ?>&urut=rating" class="bg-white border border-slate-200 hover:border-amber-400 hover:bg-amber-50 rounded-full px-5 py-2 text-sm font-semibold"><?= e($label) ?></a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Produk unggulan -->
    <section>
        <div class="flex items-end justify-between mb-4">
            <h2 class="text-2xl font-bold">⭐ Pilihan terbaik</h2>
            <a href="produk.php" class="text-sm font-semibold text-amber-600 hover:underline">Lihat semua →</a>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($unggulan as $kode => $item): ?>
                <?php require __DIR__ . '/includes/kartu-produk.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Banner voucher -->
    <section class="rounded-3xl bg-gradient-to-r from-cyan-400 to-indigo-500 p-8 md:p-10 flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold text-slate-900/70">Voucher spesial</p>
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900">Mahasiswa? Pakai <span class="bg-white/80 px-2 rounded">KAMPUS200</span></h2>
            <p class="text-slate-900/70 text-sm mt-1">Potongan Rp 200.000. Atau <b>BALE5</b> untuk diskon 5%. Masukkan di halaman keranjang.</p>
        </div>
        <a href="produk.php?kategori=pelajar" class="bg-slate-900 text-white font-bold px-6 py-3 rounded-xl hover:bg-slate-800">Laptop pelajar</a>
    </section>

    <!-- Diskon terbesar -->
    <section>
        <h2 class="text-2xl font-bold mb-4">🔥 Diskon terbesar</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($diskonBesar as $kode => $item): ?>
                <?php require __DIR__ . '/includes/kartu-produk.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Keunggulan -->
    <section class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <?php
        $keunggulan = [
            ["🛡️", "Garansi resmi", "Garansi unit hingga 2 tahun."],
            ["🔧", "Servis & upgrade", "Tambah RAM atau SSD di toko."],
            ["🚚", "Kirim aman", "Packing kayu, asuransi pengiriman."],
            ["💳", "Cicilan 0%", "Tersedia untuk kartu kredit."],
        ];
        foreach ($keunggulan as [$ikon, $judul, $isi]): ?>
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <div class="text-3xl mb-2"><?= $ikon ?></div>
                <p class="font-semibold"><?= $judul ?></p>
                <p class="text-sm text-slate-500"><?= $isi ?></p>
            </div>
        <?php endforeach; ?>
    </section>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
