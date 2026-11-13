<?php
// rekap_poin.php

// ============================================
// 1. KONEKSI DAN SESSION
// ============================================
include 'includes/cek_session.php';
include 'config/koneksi.php';

// ============================================
// 2. INPUT PENCARIAN DAN FILTER
// ============================================
$cari = isset($_GET['cari'])
    ? trim($_GET['cari'])
    : '';

$kelas_filter = isset($_GET['kelas'])
    ? trim($_GET['kelas'])
    : '';

$cari_sql = mysqli_real_escape_string($koneksi, $cari);
$kelas_sql = mysqli_real_escape_string($koneksi, $kelas_filter);

// ============================================
// 3. MENGAMBIL DATA TAHUN AJARAN AKTIF
// ============================================
$query_tahun = mysqli_query(
    $koneksi,
    "SELECT nama
     FROM t_tahun_ajaran
     WHERE status_aktif = 1
     LIMIT 1"
);

$tahun_ajaran = '-';

if ($query_tahun && mysqli_num_rows($query_tahun) > 0) {
    $data_tahun = mysqli_fetch_assoc($query_tahun);
    $tahun_ajaran = $data_tahun['nama'];
}

// ============================================
// 4. DATA KELAS
// ============================================
$query_kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_kelas
     ORDER BY nama ASC"
);

if (!$query_kelas) {
    die("Gagal mengambil data kelas: " . mysqli_error($koneksi));
}

$data_kelas = [];

while ($kelas = mysqli_fetch_assoc($query_kelas)) {
    $data_kelas[] = $kelas;
}

// ============================================
// 5. QUERY REKAP POIN SISWA
// ============================================
$sql = "SELECT
            s.id AS siswa_id,
            s.nis,
            s.nama AS nama_siswa,
            COALESCE(k.nama, '-') AS nama_kelas,
            COALESCE(p.total_poin, 0) AS total_poin,
            COALESCE(p.jumlah_pelanggaran, 0) AS jumlah_pelanggaran

        FROM t_siswa s

        LEFT JOIN (
            SELECT
                siswa_id,
                SUM(poin) AS total_poin,
                COUNT(*) AS jumlah_pelanggaran
            FROM t_pelanggaran_siswa
            GROUP BY siswa_id
        ) p ON p.siswa_id = s.id

        LEFT JOIN (
            SELECT
                siswa_id,
                MAX(kelas_id) AS kelas_id
            FROM t_kelas_siswa
            WHERE status_aktif = 1
            GROUP BY siswa_id
        ) ks ON ks.siswa_id = s.id

        LEFT JOIN t_kelas k ON k.id = ks.kelas_id

        WHERE 1=1";

// ============================================
// 6. PENCARIAN SISWA
// ============================================
if ($cari != '') {
    $sql .= " AND (
        s.nama LIKE '%$cari_sql%'
        OR s.nis LIKE '%$cari_sql%'
    )";
}

// ============================================
// 7. FILTER KELAS
// ============================================
if ($kelas_filter != '') {
    $sql .= " AND k.id = '$kelas_sql'";
}

// ============================================
// 8. URUTAN POIN TERTINGGI
// ============================================
$sql .= " ORDER BY total_poin DESC, s.nama ASC";

$query_rekap = mysqli_query($koneksi, $sql);

if (!$query_rekap) {
    die("Gagal mengambil rekap poin: " . mysqli_error($koneksi));
}

// ============================================
// 9. MENGHITUNG RINGKASAN
// ============================================
$data_rekap = [];
$total_poin_semua = 0;
$total_catatan = 0;

while ($data = mysqli_fetch_assoc($query_rekap)) {

    $data_rekap[] = $data;

    $total_poin_semua += (int) $data['total_poin'];
    $total_catatan += (int) $data['jumlah_pelanggaran'];
}

$jumlah_siswa = count($data_rekap);

// ============================================
// 10. NAMA KELAS UNTUK CETAK
// ============================================
$nama_kelas_cetak = 'Semua Kelas';

if ($kelas_filter != '') {
    foreach ($data_kelas as $kelas) {
        if ((string) $kelas['id'] === (string) $kelas_filter) {
            $nama_kelas_cetak = $kelas['nama'];
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Rekap Poin Pelanggaran Siswa</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- DATATABLES -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">

    <style>

        .angka {
            font-size: 2rem;
            font-weight: bold;
            color: #0d6efd;
        }

        /* KHUSUS CETAK */
        .header-cetak,
        .tanda-tangan {
            display: none;
        }

        @page {
            size: A4 portrait;
            margin: 2cm;
        }

        @media print {

            body {
                font-family: "Times New Roman", serif !important;
                font-size: 12pt;
                color: #000 !important;
                background: #fff !important;
            }

            /* Menyembunyikan sidebar, menu, pencarian, dan ringkasan saat dicetak */
            .col-md-3,
            .col-lg-2,
            aside,
            nav,
            .no-print,
            .dt-search,
            .dt-length,
            .dt-paging,
            .dt-info,
            .dataTables_filter,
            .dataTables_length,
            .dataTables_paginate,
            .dataTables_info {
                display: none !important;
            }

            .container-fluid,
            .row,
            main {
                display: block !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .header-cetak,
            .tanda-tangan {
                display: block !important;
            }

            .card,
            .card-body,
            .table-responsive {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                overflow: visible !important;
            }

            .card-header {
                display: none !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
                font-size: 10pt !important;
            }

            table th,
            table td {
                border: 1px solid #000 !important;
                padding: 7px !important;
                color: #000 !important;
                background: #fff !important;
            }

            table thead th {
                text-align: center !important;
                font-weight: bold !important;
            }

            .badge {
                color: #000 !important;
                background: #fff !important;
                font-weight: normal !important;
            }

            .tanda-tangan {
                margin-top: 35px;
                page-break-inside: avoid;
            }

            .tanda-tangan table,
            .tanda-tangan td {
                border: none !important;
            }

            h2, h3, h4, p {
                color: #000 !important;
            }

            .angka {
                color: #000 !important;
            }
        }

    </style>

</head>

<body>

<div class="container-fluid">

    <div class="row">

        <?php include 'layout/sidebar.php'; ?>

        <main class="col-md-9 col-lg-10 p-4">

            <div class="container-fluid">

                <!-- ================================= -->
                <!-- JUDUL HALAMAN WEBSITE -->
                <!-- ================================= -->

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 no-print">

                    <div>
                        <h2>Rekap Poin Pelanggaran Siswa</h2>

                        <p class="text-muted mb-0">
                            Ringkasan jumlah pelanggaran dan poin setiap siswa.
                        </p>
                    </div>

                    <div class="d-flex gap-2">

                        <a href="dashboard.php" class="btn btn-secondary">
                            Kembali ke Dashboard
                        </a>

                        <button type="button"
                                onclick="window.print()"
                                class="btn btn-success">
                            Cetak Rekap
                        </button>

                    </div>

                </div>

                <!-- ================================= -->
                <!-- RINGKASAN (Ditambahkan no-print agar hilang saat cetak) -->
                <!-- ================================= -->

                <div class="row g-3 mb-4 no-print">

                    <div class="col-md-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">

                                <p class="text-muted mb-1">
                                    Jumlah Siswa
                                </p>

                                <div class="angka">
                                    <?= $jumlah_siswa; ?>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">

                                <p class="text-muted mb-1">
                                    Total Poin
                                </p>

                                <div class="angka">
                                    <?= $total_poin_semua; ?>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">

                                <p class="text-muted mb-1">
                                    Jumlah Catatan Pelanggaran
                                </p>

                                <div class="angka">
                                    <?= $total_catatan; ?>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <!-- ================================= -->
                <!-- PENCARIAN DAN FILTER -->
                <!-- ================================= -->

                <div class="card shadow-sm mb-4 no-print">

                    <div class="card-header bg-primary text-white">

                        <h5 class="mb-0">
                            Pencarian dan Filter Siswa
                        </h5>

                    </div>

                    <div class="card-body">

                        <form method="GET" action="rekap_poin.php">

                            <div class="row g-3">

                                <div class="col-md-5">

                                    <label class="form-label">
                                        Cari Siswa
                                    </label>

                                    <input
                                        type="text"
                                        name="cari"
                                        class="form-control"
                                        placeholder="Masukkan nama atau NIS siswa"
                                        value="<?= htmlspecialchars($cari); ?>">

                                </div>

                                <div class="col-md-4">

                                    <label class="form-label">
                                        Filter Kelas
                                    </label>

                                    <select name="kelas" class="form-select">

                                        <option value="">
                                            Semua Kelas
                                        </option>

                                        <?php foreach ($data_kelas as $kelas) { ?>

                                            <option
                                                value="<?= (int) $kelas['id']; ?>"
                                                <?= $kelas_filter == $kelas['id'] ? 'selected' : ''; ?>>

                                                <?= htmlspecialchars($kelas['nama']); ?>

                                            </option>

                                        <?php } ?>

                                    </select>

                                </div>

                                <div class="col-md-3 d-flex align-items-end gap-2">

                                    <button type="submit" class="btn btn-primary">
                                        Cari
                                    </button>

                                    <a href="rekap_poin.php" class="btn btn-secondary">
                                        Reset
                                    </a>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>

                <!-- ================================= -->
                <!-- HEADER KHUSUS DOKUMEN CETAK -->
                <!-- ================================= -->

                <div class="header-cetak mb-4">

                    <div class="text-center">

                        <h3 class="fw-bold mb-1">
                            SMK MUHAMMADIYAH TASIKMALAYA
                        </h3>

                        <p class="mb-1">
                            Alamat: ........................................................
                        </p>

                        <p class="mb-2">
                            Telepon: ........................ Email: ........................
                        </p>

                        <hr style="border: 2px solid black; opacity: 1;">

                        <h4 class="fw-bold text-uppercase mt-3">
                            REKAPITULASI POIN PELANGGARAN SISWA
                        </h4>

                        <p class="mb-3">
                            Tahun Ajaran: <?= htmlspecialchars($tahun_ajaran); ?>
                        </p>

                    </div>

                    <div class="mb-3">

                        <p class="mb-1">
                            Kelas: <?= htmlspecialchars($nama_kelas_cetak); ?>
                        </p>

                        <p class="mb-1">
                            Tanggal Cetak: <?= date('d-m-Y'); ?>
                        </p>

                    </div>

                </div>

                <!-- ================================= -->
                <!-- TABEL REKAP -->
                <!-- ================================= -->

                <div class="card shadow-sm">

                    <div class="card-header bg-dark text-white no-print">

                        <h5 class="mb-0">
                            Data Rekap Poin Siswa
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table id="tabelRekap"
                                   class="table table-striped table-bordered table-hover align-middle w-100">

                                <thead class="table-dark">

                                    <tr>
                                        <th>No.</th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Jumlah Pelanggaran</th>
                                        <th>Total Poin</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    <?php foreach ($data_rekap as $data) { ?>

                                        <tr>

                                            <td></td>

                                            <td>
                                                <?= htmlspecialchars($data['nis'] ?? '-'); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($data['nama_siswa'] ?? '-'); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($data['nama_kelas'] ?? '-'); ?>
                                            </td>

                                            <td class="text-center">
                                                <?= (int) $data['jumlah_pelanggaran']; ?>
                                            </td>

                                            <td class="text-center">

                                                <span class="badge bg-warning text-dark">
                                                    <?= (int) $data['total_poin']; ?> poin
                                                </span>

                                            </td>

                                        </tr>

                                    <?php } ?>

                                </tbody>

                            </table>

                        </div>

                        <p class="text-muted mb-0 mt-3 no-print">

                            Total poin dihitung berdasarkan catatan pada
                            tabel t_pelanggaran_siswa.

                        </p>

                    </div>

                </div>

                <!-- ================================= -->
                <!-- TANDA TANGAN DOKUMEN -->
                <!-- ================================= -->

                <div class="tanda-tangan">

                    <table style="width:100%; margin-top:30px;">

                        <tr>

                            <td style="width:50%; text-align:center;">

                                Mengetahui,<br>
                                Kepala Sekolah

                                <br><br><br><br>

                                <strong>(................................)</strong>

                                <br>

                                NIP. ................................

                            </td>

                            <td style="width:50%; text-align:center;">

                                Tasikmalaya, <?= date('d F Y'); ?><br>
                                Petugas/Pembuat Laporan

                                <br><br><br><br>

                                <strong>(................................)</strong>

                                <br>

                                NIP. ................................

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

        </main>

    </div>

</div>

<!-- ================================= -->
<!-- JAVASCRIPT -->
<!-- ================================= -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>

$(document).ready(function () {

    $('#tabelRekap').DataTable({

        pageLength: 10,

        lengthMenu: [5, 10, 25, 50, 100],

        searching: true,

        ordering: true,

        paging: true,

        order: [[5, 'desc'], [2, 'asc']],

        columnDefs: [
            {
                orderable: false,
                searchable: false,
                targets: 0
            }
        ],

        drawCallback: function () {

            var api = this.api();

            var nomorAwal = api.page.info().start;

            api.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function (cell, i) {

                cell.innerHTML = nomorAwal + i + 1;

            });

        },

        language: {

            search: "Cari di tabel:",

            lengthMenu: "Tampilkan _MENU_ data",

            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",

            infoEmpty: "Tidak ada data rekap",

            infoFiltered: "(difilter dari _MAX_ total data)",

            zeroRecords: "Data siswa tidak ditemukan",

            emptyTable: "Belum ada data rekap poin siswa",

            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }

        }

    });

});

</script>

</body>
</html>