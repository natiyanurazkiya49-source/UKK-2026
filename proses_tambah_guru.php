
<?php
// proses_tambah_guru.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

$nip = mysqli_real_escape_string($koneksi, $_POST['nip']);
$nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
$email = mysqli_real_escape_string($koneksi, $_POST['email']);
$status_aktif = mysqli_real_escape_string($koneksi, $_POST['status_aktif']);

// Mulai transaksi
mysqli_begin_transaction($koneksi);

try {

    // Simpan akun pengguna
    $username = $nip;
    $password = password_hash($nip, PASSWORD_DEFAULT);
    $role = 'guru';

    $sql_user = "INSERT INTO t_users
                 (username, password, role)
                 VALUES ('$username', '$password', '$role')";

    if (!mysqli_query($koneksi, $sql_user)) {
        throw new Exception(mysqli_error($koneksi));
    }

    // Ambil ID user yang baru dibuat
    $user_id = mysqli_insert_id($koneksi);

    // Simpan data guru
    $sql_guru = "INSERT INTO t_guru
                 (user_id, nip, nama, email, status_aktif)
                 VALUES
                 ('$user_id', '$nip', '$nama', '$email', '$status_aktif')";

    if (!mysqli_query($koneksi, $sql_guru)) {
        throw new Exception(mysqli_error($koneksi));
    }

    // Simpan perubahan
    mysqli_commit($koneksi);

    header("Location: kelola_guru.php");
    exit;

} catch (Exception $e) {

    mysqli_rollback($koneksi);

    echo "Gagal menambahkan guru: " . $e->getMessage();

}
?>
