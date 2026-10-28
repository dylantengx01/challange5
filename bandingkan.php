<?php
// ============================================
// HALAMAN BANDINGKAN LAPTOP (maks. 3 produk)
// ============================================
require_once __DIR__ . '/includes/init.php';

// ---- Proses aksi (POST) lalu redirect ----
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!csrfCek()) {
        setFlash("error", "Sesi tidak valid, silakan coba lagi.");
    } else {
        $aksi = $_POST["aksi"] ?? "";
        if ($aksi === "toggle") {
            bandingToggle($_POST["kode"] ?? "", $produk);
        } elseif ($aksi === "kosongkan") {
            unset($_SESSION["banding"]);
            setFlash("sukses", "Daftar perbandingan dikosongkan.");
        }
    }
    // Kembali ke halaman asal jika masih di situs ini, selain itu ke halaman banding
    $asal = $_SERVER["HTTP_REFERER"] ?? "";
    $host = $_SERVER["HTTP_HOST"] ?? "";
    $kembali = ($asal !== "" && parse_url($asal, PHP_URL_HOST) === explode(":", $host)[0]) ? $asal : "bandingkan.php";
    header("Location: " . $kembali);
    exit;
}

$judulHalaman = "Bandingkan";
$halamanAktif = "bandingkan.php";

$daftar = array_filter(daftarBanding(), fn($k) => isset($produk[$k]));

// Kumpulkan semua nama spesifikasi dari laptop yang dipilih
$labelSpek = [];
foreach ($daftar as $k) {
    foreach (array_keys($produk[$k]["spesifikasi"]) as $label) {
        $labelSpek[$label] = true;
    }
}

require __DIR__ . '/includes/header.php';
?>

<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-extrabold mb-1">⚖️ Bandingkan Laptop</h1>
    <p class="text-slate-500 mb-6">Pilih hingga <?= MAKS_BANDING ?> laptop dari katalog untuk dibandingkan berdampingan.</p>

    <?php if (count($daftar) < 1): ?>
        <div class="text-center bg-white rounded-2xl border border-dashed border-slate-300 py-16">
            <div class="text-6xl mb-3">⚖️</div>
            <p class="font-semibold mb-1">Belum ada laptop yang dipilih</p>
            <p class="text-sm text-slate-500">Klik tombol ⚖️ pada kartu laptop untuk menambahkannya.</p>
            <a href="produk.php" class="inline-block mt-4 bg-amber-400 hover:bg-amber-300 font-bold px-6 py-2 rounded-xl">Pilih laptop</a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto bg-white rounded-2xl border border-slate-200">
            <table class="w-full text-sm min-w-[40rem]">
                <thead>
                    <tr class="border-b border-slate-200 align-top">
                        <th class="p-4 w-40"></th>
                        <?php foreach ($daftar as $k): $p = $produk[$k]; ?>
                            <th class="p-4 text-left">
                                <div class="text-5xl mb-2"><?= $p["ikon"] ?></div>
                                <a href="detail.php?kode=<?= e($k) ?>" class="font-bold hover:text-amber-600"><?= e($p["nama"]) ?></a>
                                <form method="post" class="mt-2">
                                    <?= csrfField() ?>
                                    <input type="hidden" name="aksi" value="toggle">
                                    <input type="hidden" name="kode" value="<?= e($k) ?>">
                                    <button class="text-xs text-red-600 hover:underline font-normal">Hapus</button>
                                </form>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-slate-100">
                        <td class="p-4 text-slate-500">Harga</td>
                        <?php foreach ($daftar as $k): $p = $produk[$k]; ?>
                            <td class="p-4 font-bold"><?= formatRupiah(hargaSetelahDiskon($p["harga"], $p["diskon"])) ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <tr class="border-b border-slate-100">
                        <td class="p-4 text-slate-500">Rating</td>
                        <?php foreach ($daftar as $k): $p = $produk[$k]; ?>
                            <td class="p-4 text-amber-500"><?= bintang($p["rating"]) ?> <span class="text-slate-400"><?= number_format($p["rating"], 1) ?></span></td>
                        <?php endforeach; ?>
                    </tr>
                    <?php foreach (array_keys($labelSpek) as $label): ?>
                        <tr class="border-b border-slate-100">
                            <td class="p-4 text-slate-500"><?= e($label) ?></td>
                            <?php foreach ($daftar as $k): ?>
                                <td class="p-4"><?= e($produk[$k]["spesifikasi"][$label] ?? "-") ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td class="p-4 text-slate-500">Cocok untuk</td>
                        <?php foreach ($daftar as $k): ?>
                            <td class="p-4 text-slate-600"><?= e($produk[$k]["cocok"]) ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <td class="p-4"></td>
                        <?php foreach ($daftar as $k): $p = $produk[$k]; ?>
                            <td class="p-4">
                                <form method="post" action="keranjang.php">
                                    <?= csrfField() ?>
                                    <input type="hidden" name="aksi" value="tambah">
                                    <input type="hidden" name="kode" value="<?= e($k) ?>">
                                    <button <?= $p["stok"] === 0 ? "disabled" : "" ?> class="rounded-xl px-4 py-2 text-sm font-semibold bg-amber-400 hover:bg-amber-300 text-slate-900 disabled:bg-slate-200 disabled:text-slate-400">
                                        <?= $p["stok"] === 0 ? "Stok habis" : "🛒 Beli" ?>
                                    </button>
                                </form>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>

        <form method="post" class="mt-4">
            <?= csrfField() ?>
            <input type="hidden" name="aksi" value="kosongkan">
            <button class="text-sm text-slate-500 hover:text-red-600 hover:underline">Kosongkan perbandingan</button>
        </form>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
