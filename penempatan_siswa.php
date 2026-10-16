
<?php
// penempatan_siswa.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil daftar siswa
$siswa = mysqli_query(
    $koneksi,
    "SELECT id, nis, nama
     FROM t_siswa
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

// Ambil daftar tahun ajaran
$tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_tahun_ajaran
     WHERE status_aktif = 1
     ORDER BY id DESC"
);

// Ambil daftar kelas
$kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama, tingkat, jurusan
     FROM t_kelas
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

// Ambil data penempatan siswa
$sql = "SELECT
            ks.id,
            s.nis,
            s.nama AS nama_siswa,
            ta.nama AS tahun_ajaran,
            k.nama AS nama_kelas,
            k.tingkat,
            k.jurusan,
            ks.tanggal_mulai,
            ks.tanggal_selesai,
            ks.status_aktif
        FROM t_kelas_siswa ks
        LEFT JOIN t_siswa s ON ks.siswa_id = s.id
        LEFT JOIN t_tahun_ajaran ta
            ON ks.tahun_ajaran_id = ta.id
        LEFT JOIN t_kelas k ON ks.kelas_id = k.id
        ORDER BY ks.id DESC";

$hasil = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Penempatan Siswa</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <style>
        body {
            margin: 0;
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }

        .sidebar .nav-link {
            color: white;
            margin-bottom: 5px;
        }

        .sidebar .nav-link:hover {
            background-color: #495057;
        }

        .sidebar .nav-link.active {
            background-color: #007bff;
        }

        .content {
            min-width: 0;
        }

        .form-container,
        .table-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .table-responsive {
            width: 100%;
        }

        .table {
            margin-bottom: 0;
        }

        .table th,
        .table td {
            vertical-align: middle;
            white-space: nowrap;
        }

        @media (max-width: 767px) {
            .sidebar {
                min-height: auto;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 sidebar p-3">

            <h4 class="text-white mb-4">
                My Website
            </h4>

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
                    <a href="penempatan_siswa.php"
                       class="nav-link active">
                        Penempatan Siswa
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_wali_kelas.php"
                       class="nav-link">
                        Wali Kelas
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_pelanggaran_kategori.php"
                       class="nav-link">
                        Pelanggaran Kategori
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="laporan.php" class="nav-link">
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
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4 content">

            <h1 class="mb-2">Penempatan Siswa</h1>

            <p>
                <a href="dashboard.php">
                    Kembali ke Dashboard
                </a>
            </p>

            <!-- PESAN SUKSES -->
            <?php if (isset($_SESSION['pesan_sukses'])) { ?>
                <div class="alert alert-success">
                    <?php
                    echo htmlspecialchars($_SESSION['pesan_sukses']);
                    unset($_SESSION['pesan_sukses']);
                    ?>
                </div>
            <?php } ?>

            <!-- PESAN ERROR -->
            <?php if (isset($_SESSION['pesan_error'])) { ?>
                <div class="alert alert-danger">
                    <?php
                    echo htmlspecialchars($_SESSION['pesan_error']);
                    unset($_SESSION['pesan_error']);
                    ?>
                </div>
            <?php } ?>

            <!-- FORM PENEMPATAN SISWA -->
            <div class="form-container">

                <h3 class="mb-3">Form Penempatan Siswa</h3>

                <form action="proses_simpan_penempatan.php"
                      method="POST">

                    <div class="form-group row">
                        <label class="col-md-3 col-lg-2 col-form-label">
                            Siswa
                        </label>

                        <div class="col-md-9 col-lg-6">
                            <select name="siswa_id"
                                    class="form-control"
                                    required>
                                <option value="">
                                    -- Pilih Siswa --
                                </option>

                                <?php while ($s = mysqli_fetch_assoc($siswa)) { ?>
                                    <option value="<?php echo $s['id']; ?>">
                                        <?php
                                        echo htmlspecialchars(
                                            $s['nis'] . ' - ' . $s['nama']
                                        );
                                        ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-lg-2 col-form-label">
                            Tahun Ajaran
                        </label>

                        <div class="col-md-9 col-lg-6">
                            <select name="tahun_ajaran_id"
                                    class="form-control"
                                    required>
                                <option value="">
                                    -- Pilih Tahun Ajaran --
                                </option>

                                <?php while ($ta = mysqli_fetch_assoc($tahun)) { ?>
                                    <option value="<?php echo $ta['id']; ?>">
                                        <?php echo htmlspecialchars($ta['nama']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-lg-2 col-form-label">
                            Kelas
                        </label>

                        <div class="col-md-9 col-lg-6">
                            <select name="kelas_id"
                                    class="form-control"
                                    required>
                                <option value="">
                                    -- Pilih Kelas --
                                </option>

                                <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
                                    <option value="<?php echo $k['id']; ?>">
                                        <?php
                                        echo htmlspecialchars(
                                            $k['nama'] . ' - Tingkat ' .
                                            $k['tingkat'] . ' - ' .
                                            $k['jurusan']
                                        );
                                        ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-lg-2 col-form-label">
                            Tanggal Mulai
                        </label>

                        <div class="col-md-9 col-lg-6">
                            <input type="date"
                                   name="tanggal_mulai"
                                   class="form-control"
                                   required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-lg-2 col-form-label">
                            Tanggal Selesai
                        </label>

                        <div class="col-md-9 col-lg-6">
                            <input type="date"
                                   name="tanggal_selesai"
                                   class="form-control"
                                   required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-lg-2 col-form-label">
                            Status
                        </label>

                        <div class="col-md-9 col-lg-6">
                            <select name="status_aktif"
                                    class="form-control"
                                    required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Simpan Penempatan
                    </button>

                </form>
            </div>

            <!-- TABEL DATA PENEMPATAN SISWA -->
            <div class="table-container">

                <h3 class="mb-3">Data Penempatan Siswa</h3>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">

                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Tahun Ajaran</th>
                                <th>Kelas</th>
                                <th>Tingkat</th>
                                <th>Jurusan</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $no = 1;

                            while ($row = mysqli_fetch_assoc($hasil)) {
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars($row['nis'] ?? '-');
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars($row['nama_siswa'] ?? '-');
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars($row['tahun_ajaran'] ?? '-');
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars($row['nama_kelas'] ?? '-');
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars($row['tingkat'] ?? '-');
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars($row['jurusan'] ?? '-');
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars($row['tanggal_mulai']);
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars($row['tanggal_selesai']);
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo $row['status_aktif'] == 1
                                            ? 'Aktif'
                                            : 'Tidak Aktif';
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>

                            <?php if (mysqli_num_rows($hasil) == 0) { ?>
                                <tr>
                                    <td colspan="10" class="text-center">
                                        Belum ada data penempatan siswa.
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>

                    </table>
                </div>

            </div>

        </main>
        <!-- AKHIR KONTEN UTAMA -->

    </div>
</div>

<!-- Bootstrap JavaScript -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>

</body>
</html>
