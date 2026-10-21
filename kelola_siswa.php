<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT * FROM t_siswa ORDER BY nama ASC";
$hasil = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Siswa</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <!-- TAMBAHKAN CSS INI AGAR SIDEBAR FULL KE BAWAH -->
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
                <a href="kelola_siswa.php" class="nav-link active">Data Siswa</a>
            </li>
            <li class="nav-item mb-2">
                <a href="kelola_guru.php" class="nav-link text-white">Data Guru</a>
            </li>
            <li class="nav-item mb-2">
                <a href="kelola_kelas.php" class="nav-link text-white">Kelas</a>
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

        <h2 class="mb-4">Kelola Siswa</h2>

        <p>
            <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
            <a href="tambah_siswa.php" class="btn btn-primary">+ Tambah Siswa</a>
        </p>

        <div class="table-responsive">
            <table id="tabelSiswa" class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>NIS</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Tanggal Lahir</th>
                        <th>Alamat</th>
                        <th>Status Aktif</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($siswa = mysqli_fetch_assoc($hasil)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($siswa['nis'] ?? '') ?></td>
                        <td><?= htmlspecialchars($siswa['nisn'] ?? '') ?></td>
                        <td><?= htmlspecialchars($siswa['nama'] ?? '') ?></td>
                        <td><?= htmlspecialchars($siswa['jenis_kelamin'] ?? '') ?></td>
                        <td><?= htmlspecialchars($siswa['tanggal_lahir'] ?? '') ?></td>
                        <td><?= htmlspecialchars($siswa['alamat'] ?? '') ?></td>
                        <td><?= htmlspecialchars($siswa['status_aktif'] ?? 'Aktif') ?></td>
                        <td>
                            <a href="edit_siswa.php?nis=<?= urlencode($siswa['nis'] ?? '') ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="hapus_siswa.php?nis=<?= urlencode($siswa['nis'] ?? '') ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data siswa ini?')">Hapus</a>
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

<!-- JavaScript DataTables -->
<script>
$(document).ready(function() {
    $('#tabelSiswa').DataTable({
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "infoEmpty": "Tidak ada data",
            "zeroRecords": "Data tidak ditemukan",
            "emptyTable": "Belum ada data siswa",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Berikutnya",
                "previous": "Sebelumnya"
            }
        },
        "pageLength": 10,
        "lengthMenu": [5, 10, 25, 50, 100],
        "order": [[2, "asc"]]
    });
});
</script>

</body>
</html>