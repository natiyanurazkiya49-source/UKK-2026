<?php
// kelola_wali_kelas.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT
            w.*,
            k.nama AS nama_kelas,
            g.nama AS nama_guru
        FROM t_wali_kelas w
        LEFT JOIN t_kelas k ON w.kelas_id = k.id
        LEFT JOIN t_guru g ON w.guru_id = g.id
        ORDER BY w.id DESC";

$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Wali Kelas</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- DataTables Bootstrap 5 -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN -->
        <main class="col-md-9 col-lg-10 p-4">

            <h2>Kelola Wali Kelas</h2>
            <p>Selamat datang di halaman Wali Kelas.</p>

            <div class="mb-3">
                <a href="dashboard.php" class="btn btn-secondary">
                    Kembali ke Dashboard
                </a>

                <a href="tambah_wali_kelas.php" class="btn btn-primary">
                    Tambah Wali Kelas
                </a>
            </div>

            <!-- TABEL WALI KELAS -->
            <div class="table-responsive">

                <table id="tabelWaliKelas"
                       class="table table-striped table-bordered table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Tahun Ajaran ID</th>
                            <th>Kelas</th>
                            <th>Guru</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php
                    $no = 1;

                    while ($row = mysqli_fetch_assoc($hasil)) {
                    ?>

                        <tr>
                            <!-- NOMOR URUT OTOMATIS -->
                            <td><?php echo $no++; ?></td>

                            <td>
                                <?php echo htmlspecialchars($row['tahun_ajaran_id'] ?? ''); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['nama_kelas'] ?? '-'); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['nama_guru'] ?? '-'); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['tanggal_mulai'] ?? ''); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['tanggal_selesai'] ?? ''); ?>
                            </td>

                            <td>
                                <?php
                                echo ($row['status_aktif'] == 1)
                                    ? 'Aktif'
                                    : 'Tidak Aktif';
                                ?>
                            </td>

                            <td>
                                <a href="edit_wali_kelas.php?id=<?php
                                    echo urlencode($row['id']);
                                ?>"
                                   class="btn btn-primary btn-sm mb-1">
                                    Edit
                                </a>

                                <a href="hapus_wali_kelas.php?id=<?php
                                    echo urlencode($row['id']);
                                ?>"
                                   class="btn btn-danger btn-sm mb-1"
                                   onclick="return confirm('Yakin ingin menghapus data wali kelas ini?');">
                                    Hapus
                                </a>
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

<!-- Bootstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<!-- Pengaturan DataTables -->
<script>
$(document).ready(function () {

    $('#tabelWaliKelas').DataTable({

        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],

        // Data awal diurutkan berdasarkan ID terbaru
        order: [[1, 'asc']],

        ordering: true,
        searching: true,
        paging: true,

        language: {
            search: "Cari wali kelas:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data wali kelas",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data wali kelas tidak ditemukan",
            emptyTable: "Belum ada data wali kelas",

            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        },

        // Kolom nomor dan aksi tidak dapat diurutkan
        columnDefs: [
            {
                targets: [0, 7],
                orderable: false,
                searchable: false
            }
        ],

        // Nomor otomatis mengikuti halaman tabel
        drawCallback: function () {
            var api = this.api();
            var info = api.page.info();

            api.column(0, {
                page: 'current'
            }).nodes().each(function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            });
        }

    });

});
</script>

</body>
</html>