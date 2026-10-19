
<?php
include "config/koneksi.php";

$data = mysqli_query($koneksi, "
    SELECT
        ps.id,
        s.nis,
        s.nama,
        pk.nama AS pelanggaran
    FROM t_pelanggaran_siswa ps
    JOIN t_siswa s ON ps.siswa_id = s.id
    JOIN t_pelanggaran_kategori pk ON ps.pelanggaran_id = pk.id
    ORDER BY ps.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laporan Pelanggaran Siswa</title>

    <!-- Bootstrap -->
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <!-- CSS DataTables -->
    <link rel="stylesheet"
    href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .sidebar {
            min-height: 100vh;
        }

        .nav-link {
            color: white;
        }

        .nav-link:hover {
            background-color: #495057;
            color: white;
        }

        .konten {
            padding: 25px;
        }

        .table {
            background-color: white;
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 bg-dark sidebar p-3">

            <h4 class="text-white mb-4">My Website</h4>

            <ul class="nav nav-pills flex-column">

                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_siswa.php" class="nav-link">
                        Data Siswa
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_guru.php" class="nav-link">
                        Data Guru
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_kelas.php" class="nav-link">
                        Kelas
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_tahun_ajaran.php" class="nav-link">
                        Tahun Ajaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="penempatan_siswa.php" class="nav-link">
                        Penempatan Siswa
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_wali_kelas.php" class="nav-link">
                        Wali Kelas
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_pelanggaran_kategori.php" class="nav-link">
                        Pelanggaran Kategori
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="laporan.php" class="nav-link active">
                        Laporan
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="aboutme.php" class="nav-link">
                        About Me
                    </a>
                </li>

                <hr class="bg-secondary">

                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger">
                        Logout
                    </a>
                </li>

            </ul>
        </div>

        <!-- KONTEN -->
        <main class="col-md-9 col-lg-10 konten">

            <h2 class="mb-4">Laporan Pelanggaran Siswa</h2>

            <!-- TOMBOL -->
            <div class="mb-3">

                <a href="dashboard.php"
                   class="btn btn-secondary mb-2">
                    Kembali
                </a>

                <a href="cetak_laporan.php"
                   target="_blank"
                   class="btn btn-primary mb-2">
                    Cetak Laporan
                </a>

                <a href="export_laporan.php"
                   class="btn btn-success mb-2">
                    Export Excel
                </a>

            </div>

            <!-- TABEL LAPORAN -->
            <div class="table-responsive">

                <table id="tabelLaporan"
                       class="table table-bordered table-striped">

                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Jenis Pelanggaran</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php
                    $no = 1;

                    while ($row = mysqli_fetch_assoc($data)) {
                    ?>

                        <tr>
                            <td><?php echo $no++; ?></td>

                            <td><?php echo htmlspecialchars($row['nis']); ?></td>

                            <td><?php echo htmlspecialchars($row['nama']); ?></td>

                            <td><?php echo htmlspecialchars($row['pelanggaran']); ?></td>
                        </tr>

                    <?php } ?>

                    </tbody>
                </table>

            </div>

        </main>
    </div>
</div>

<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<!-- PENGATURAN DATATABLES -->
<script>
$(document).ready(function() {

    $('#tabelLaporan').DataTable({

        "pageLength": 10,

        "lengthMenu": [5, 10, 25, 50],

        "ordering": true,

        "searching": true,

        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "infoEmpty": "Tidak ada data",
            "zeroRecords": "Data tidak ditemukan",
            "emptyTable": "Belum ada data pelanggaran",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Berikutnya",
                "previous": "Sebelumnya"
            }
        }

    });

});
</script>

</body>
</html>
