<?php
// dashboard.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Menghitung jumlah seluruh siswa
$query_siswa = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM t_siswa"
);
$data_siswa = mysqli_fetch_assoc($query_siswa);
$total_siswa = $data_siswa['total'] ?? 0;

// Menghitung jumlah seluruh guru
$query_guru = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM t_guru"
);
$data_guru = mysqli_fetch_assoc($query_guru);
$total_guru = $data_guru['total'] ?? 0;

// Menghitung jumlah seluruh kelas
$query_kelas = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM t_kelas"
);
$data_kelas = mysqli_fetch_assoc($query_kelas);
$total_kelas = $data_kelas['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <h2 class="mb-2">Dashboard</h2>

            <p class="text-muted mb-4">
                Selamat datang di halaman dashboard.
                Berikut ringkasan data sekolah saat ini.
            </p>

            <div class="row">

                <!-- CARD DATA SISWA -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm border-primary h-100">
                        <div class="card-body">

                            <h5 class="card-title text-primary">
                                Data Siswa
                            </h5>

                            <h2 class="fw-bold">
                                <?= number_format((int)$total_siswa, 0, ',', '.') ?>
                            </h2>

                            <p class="text-muted mb-3">
                                Total siswa terdaftar
                            </p>

                            <a href="kelola_siswa.php"
                               class="btn btn-primary btn-sm">
                                Kelola Siswa
                            </a>

                        </div>
                    </div>
                </div>

                <!-- CARD DATA GURU -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm border-success h-100">
                        <div class="card-body">

                            <h5 class="card-title text-success">
                                Data Guru
                            </h5>

                            <h2 class="fw-bold">
                                <?= number_format((int)$total_guru, 0, ',', '.') ?>
                            </h2>

                            <p class="text-muted mb-3">
                                Total guru terdaftar
                            </p>

                            <a href="kelola_guru.php"
                               class="btn btn-success btn-sm">
                                Kelola Guru
                            </a>

                        </div>
                    </div>
                </div>

                <!-- CARD DATA KELAS -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm border-warning h-100">
                        <div class="card-body">

                            <h5 class="card-title text-warning">
                                Data Kelas
                            </h5>

                            <h2 class="fw-bold">
                                <?= number_format((int)$total_kelas, 0, ',', '.') ?>
                            </h2>

                            <p class="text-muted mb-3">
                                Total kelas terdaftar
                            </p>

                            <a href="kelola_kelas.php"
                               class="btn btn-warning btn-sm">
                                Kelola Kelas
                            </a>

                        </div>
                    </div>
                </div>

            </div>

        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>