<?php
// hapus_pelanggaran_kategori.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Periksa apakah ID tersedia
if (!isset($_GET['id']) || !ctype_digit($_GET['id']) || (int) $_GET['id'] < 1) {
    echo "<script>
        alert('ID kategori pelanggaran tidak valid!');
        window.location.href = 'kelola_pelanggaran_kategori.php';
    </script>";
    exit;
}

$id = (int) $_GET['id'];

// Periksa apakah data kategori tersedia
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id FROM t_pelanggaran_kategori WHERE id = ?"
);

if (!$stmt) {
    die("Gagal menyiapkan query.");
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($stmt);

if (!$data) {
    echo "<script>
        alert('Data kategori pelanggaran tidak ditemukan!');
        window.location.href = 'kelola_pelanggaran_kategori.php';
    </script>";
    exit;
}

// Hapus data kategori
$stmt = mysqli_prepare(
    $koneksi,
    "DELETE FROM t_pelanggaran_kategori WHERE id = ?"
);

if (!$stmt) {
    die("Gagal menyiapkan query penghapusan.");
}

mysqli_stmt_bind_param($stmt, "i", $id);
$berhasil = mysqli_stmt_execute($stmt);

if ($berhasil && mysqli_stmt_affected_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);

    echo "<script>
        alert('Kategori pelanggaran berhasil dihapus!');
        window.location.href = 'kelola_pelanggaran_kategori.php';
    </script>";
    exit;
}

mysqli_stmt_close($stmt);

// Jika penghapusan gagal, misalnya karena relasi foreign key
echo "<script>
    alert('Kategori gagal dihapus! Data mungkin masih digunakan pada tabel lain.');
    window.location.href = 'kelola_pelanggaran_kategori.php';
</script>";
exit;
?>