<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);

    mysqli_begin_transaction($koneksi);

    try {

        // Hapus data penempatan kelas siswa
        mysqli_query($koneksi, "
            DELETE FROM t_kelas_siswa
            WHERE siswa_id = $id
        ");

        // Hapus data siswa
        mysqli_query($koneksi, "
            DELETE FROM t_siswa
            WHERE id = $id
        ");

        mysqli_commit($koneksi);

        echo "<script>
            alert('Data siswa berhasil dihapus!');
            window.location='kelola_siswa.php';
        </script>";

    } catch (mysqli_sql_exception $e) {

        mysqli_rollback($koneksi);

        echo "<script>
            alert('Data siswa gagal dihapus karena masih memiliki relasi dengan tabel lain!');
            window.location='kelola_siswa.php';
        </script>";
    }
}
?>