<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT * FROM t_kelas ORDER BY nama ASC";
$hasil = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Kelas</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <!-- TAMBAHKAN CSS INI AGAR FULL KE BAWAH -->
    <style>
        html, body {
            height: 100%;
        }
    </style>
</head>

<body>

<!-- Tambahkan h-100 di sini -->
<div class="container-fluid h-100">
<div class="row h-100">

    <!-- SIDEBAR -->
    <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">
        <h4 class="text-white mb-4">Pelanggaran Siswa</h4>

        <ul class="nav nav-pills flex-column">
            <li class="nav-item mb-2">
                <a href="dashboard.php" class="nav-link text-white">Dashboard</a>
            </li>
            <li class="nav-item mb-2">
                <a href="kelola_siswa.php" class="nav-link text-white">Data Siswa</a>
            </li>
            <li class="nav-item mb-2">
                <a href="kelola_guru.php" class="nav-link text-white">Data Guru</a>
            </li>
            <li class="nav-item mb-2">
                <a href="kelola_kelas.php" class="nav-link active">Kelas</a>
            </li>
            <li class="nav-item mb-2">
                <a href="kelola_tahun_ajaran.php" class="nav-link text-white">Tahun Ajaran</a>
            </li>
            <li class="nav-item mb-2">
                <a href="penempatan_siswa.php" class="nav-link text-white">Penempatan Siswa</a>
            </li>
            <li class="nav-item mb-2">
                <a href="kelola_wali_kelas.php" class="nav-link text-white">Wali Kelas</a>
            </li>
            <li class="nav-item mb-2">
                <a href="kelola_pelanggaran_kategori.php" class="nav-link text-white">Pelanggaran Kategori</a>
            </li>
            <li class="nav-item mb-2">
                <a href="laporan.php" class="nav-link text-white">Laporan</a>
            </li>
            <li class="nav-item mb-2">
                <a href="aboutme.php" class="nav-link text-white">About Me</a>
            </li>

            <hr class="border-secondary">

            <li class="nav-item">
                <a href="logout.php" class="nav-link text-danger">Logout</a>
            </li>
        </ul>
    </div>

    <!-- KONTEN -->
    <main class="col-md-9 col-lg-10 p-4 overflow-auto">

        <h2 class="mb-4">Kelola Kelas</h2>

        <p>
            <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
            <a href="tambah_kelas.php" class="btn btn-primary">+ Tambah Kelas</a>
        </p>

        <!-- TABEL KELAS -->
        <div class="table-responsive">
            <table id="tabelKelas" class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nama Kelas</th>
                        <th>Tingkat</th>
                        <th>Jurusan</th>
                        <th>Status Aktif</th>
                        <th>Dibuat</th>
                        <th>Diubah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($hasil)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['nama'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['tingkat'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['jurusan'] ?? '') ?></td>
                        <td>
                            <?php 
                            echo (($row['status_aktif'] ?? 0) == 1) ? "Aktif" : "Tidak Aktif"; 
                            ?>
                        </td>
                        <td><?= htmlspecialchars($row['created_at'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['updated_at'] ?? '') ?></td>
                        <td>
                            <a href="edit_kelas.php?id=<?= urlencode($row['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="hapus_kelas.php?id=<?= urlencode($row['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data kelas ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

    </main>
</div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    $('#tabelKelas').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            zeroRecords: "Data kelas tidak ditemukan",
            emptyTable: "Belum ada data kelas",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        },
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [[1, "asc"]]
    });
});
</script>

</body>
</html>