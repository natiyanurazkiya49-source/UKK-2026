
<?php
// laporan.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// ============================================
// 1. EXPORT EXCEL
// ============================================

if (isset($_GET['export']) && $_GET['export'] === 'excel') {

    $sqlExcel = "
        SELECT
            s.nis,
            s.nama AS nama_siswa,
            p.kode,
            p.nama AS nama_pelanggaran,
            p.poin
        FROM t_pelanggaran_siswa ps
        JOIN t_siswa s
            ON ps.siswa_id = s.id
        JOIN t_pelanggaran p
            ON ps.pelanggaran_id = p.id
        ORDER BY ps.id DESC
    ";

    $dataExcel = mysqli_query($koneksi, $sqlExcel);

    if (!$dataExcel) {
        die("Gagal mengambil data: " .
            htmlspecialchars(mysqli_error($koneksi), ENT_QUOTES, 'UTF-8'));
    }

    // Header unduhan Excel
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=laporan_pelanggaran.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Membantu Excel membaca karakter UTF-8
    echo "\xEF\xBB\xBF";

    echo '<table border="1">';
    echo '<tr>';
    echo '<th>No</th>';
    echo '<th>NIS</th>';
    echo '<th>Nama Siswa</th>';
    echo '<th>Kode Pelanggaran</th>';
    echo '<th>Jenis Pelanggaran</th>';
    echo '<th>Poin</th>';
    echo '</tr>';

    $no = 1;

    while ($row = mysqli_fetch_assoc($dataExcel)) {

        echo '<tr>';

        echo '<td>' . $no++ . '</td>';

        echo '<td>' .
            htmlspecialchars((string) $row['nis'], ENT_QUOTES, 'UTF-8') .
            '</td>';

        echo '<td>' .
            htmlspecialchars((string) $row['nama_siswa'], ENT_QUOTES, 'UTF-8') .
            '</td>';

        echo '<td>' .
            htmlspecialchars((string) ($row['kode'] ?? ''), ENT_QUOTES, 'UTF-8') .
            '</td>';

        echo '<td>' .
            htmlspecialchars((string) $row['nama_pelanggaran'], ENT_QUOTES, 'UTF-8') .
            '</td>';

        echo '<td>' . (int) $row['poin'] . '</td>';

        echo '</tr>';
    }

    echo '</table>';

    mysqli_free_result($dataExcel);
    exit;
}


// ============================================
// 2. MENGAMBIL DATA LAPORAN
// ============================================

$sql = "
    SELECT
        ps.id,
        s.nis,
        s.nama AS nama_siswa,
        p.kode,
        p.nama AS nama_pelanggaran,
        p.poin
    FROM t_pelanggaran_siswa ps
    JOIN t_siswa s
        ON ps.siswa_id = s.id
    JOIN t_pelanggaran p
        ON ps.pelanggaran_id = p.id
    ORDER BY ps.id DESC
";

$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Gagal mengambil laporan: " .
        htmlspecialchars(mysqli_error($koneksi), ENT_QUOTES, 'UTF-8'));
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan Pelanggaran Siswa</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- DataTables Bootstrap 5 -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">

    <style>

        body {
            background-color: #f8f9fa;
        }

        .card-header {
            background-color: #212529;
            color: white;
            font-weight: bold;
        }

        .table thead th {
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        /* Pengaturan cetak PDF */
        @media print {

            body {
                background: #fff !important;
                font-size: 12px;
            }

            .sidebar,
            nav,
            .no-print,
            .dataTables_filter,
            .dataTables_length,
            .dataTables_info,
            .dataTables_paginate,
            .dataTables_processing {
                display: none !important;
            }

            main {
                width: 100% !important;
                max-width: 100% !important;
                flex: 0 0 100% !important;
                min-height: auto !important;
                padding: 0 !important;
            }

            .container-fluid,
            .row {
                display: block !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .card-header {
                border: none !important;
                padding: 0 0 10px 0 !important;
                color: #000 !important;
                background: #fff !important;
            }

            .card-body {
                padding: 0 !important;
            }

            .table-responsive {
                overflow: visible !important;
            }

            #tabelLaporan {
                width: 100% !important;
                border-collapse: collapse !important;
                font-size: 10px;
            }

            #tabelLaporan th,
            #tabelLaporan td {
                border: 1px solid #000 !important;
                padding: 5px !important;
                color: #000 !important;
                background: #fff !important;
            }

            h1 {
                font-size: 20px;
                color: #000 !important;
            }

            @page {
                size: landscape;
                margin: 1.5cm;
            }
        }

    </style>

</head>

<body>

<div class="container-fluid">

    <div class="row">

        <!-- SIDEBAR -->
        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <!-- JUDUL HALAMAN -->
            <h1 class="mb-3">Laporan Pelanggaran Siswa</h1>

            <!-- TOMBOL NAVIGASI DAN EXPORT -->
            <div class="mb-3 no-print">

                <a href="dashboard.php" class="btn btn-secondary">
                    Kembali ke Dashboard
                </a>

                <button type="button"
                        onclick="cetakPDF()"
                        class="btn btn-danger">
                    Cetak PDF
                </button>

                <a href="laporan.php?export=excel"
                   class="btn btn-success">
                    Export Excel
                </a>

            </div>


            <!-- CARD DATA LAPORAN -->
            <div class="card shadow-sm">

                <!-- HEADER CARD -->
                <div class="card-header">
                    Data Laporan Pelanggaran
                </div>

                <!-- BODY CARD -->
                <div class="card-body">

                    <div class="table-responsive">

                        <table id="tabelLaporan"
                               class="table table-striped table-bordered table-hover align-middle w-100">

                            <!-- HEADER TABEL -->
                            <thead class="table-dark">

                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Kode Pelanggaran</th>
                                    <th>Jenis Pelanggaran</th>
                                    <th>Poin</th>
                                </tr>

                            </thead>

                            <!-- ISI TABEL -->
                            <tbody>

                                <?php while ($row = mysqli_fetch_assoc($hasil)) { ?>

                                    <tr>

                                        <!-- Nomor urut otomatis -->
                                        <td></td>

                                        <td>
                                            <?= htmlspecialchars(
                                                (string) $row['nis'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars(
                                                (string) $row['nama_siswa'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars(
                                                (string) ($row['kode'] ?? ''),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars(
                                                (string) $row['nama_pelanggaran'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ); ?>
                                        </td>

                                        <td>
                                            <?= (int) $row['poin']; ?>
                                        </td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </main>

    </div>

</div>


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

<!-- DataTables Bootstrap 5 -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>


<!-- INISIALISASI DATATABLES -->
<script>

var panjangHalamanSebelumCetak = 10;

$(document).ready(function () {

    var tabel = $('#tabelLaporan').DataTable({

        language: {

            search: "Cari:",

            lengthMenu: "Tampilkan _MENU_ data",

            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",

            infoEmpty: "Tidak ada data",

            infoFiltered: "(difilter dari _MAX_ total data)",

            zeroRecords: "Data tidak ditemukan",

            emptyTable: "Belum ada laporan pelanggaran",

            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }

        },

        pageLength: 10,

        lengthMenu: [5, 10, 25, 50, 100],

        order: [[2, 'asc']],

        columnDefs: [
            {
                targets: 0,
                orderable: false,
                searchable: false
            }
        ],

        // Nomor urut otomatis
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


// ============================================
// FUNGSI CETAK PDF
// ============================================

function cetakPDF() {

    var tabel = $('#tabelLaporan').DataTable();

    // Simpan jumlah data per halaman
    panjangHalamanSebelumCetak = tabel.page.len();

    // Tampilkan semua data
    tabel.page.len(-1).draw();

    // Jalankan cetak browser
    setTimeout(function () {

        window.print();

    }, 300);

}


// Kembalikan pagination setelah cetak selesai
window.addEventListener('afterprint', function () {

    var tabel = $('#tabelLaporan').DataTable();

    tabel.page.len(panjangHalamanSebelumCetak).draw();

});

</script>

</body>
</html>