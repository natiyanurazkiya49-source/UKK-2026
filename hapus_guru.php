
<?php
session_start();

include 'includes/cek_session.php';
include 'config/koneksi.php';

$id = $_GET['id'];

// Ambil nama guru
$cek = mysqli_query($koneksi, "SELECT nama FROM t_guru WHERE id = '$id'");
$data = mysqli_fetch_assoc($cek);

// Hapus guru
$sql = "DELETE FROM t_guru WHERE id = '$id'";

if (mysqli_query($koneksi, $sql)) {
    header('Location: kelola_guru.php');
    exit;
} else {
    echo "Gagal menghapus data guru: " . mysqli_error($koneksi);
}
?>
