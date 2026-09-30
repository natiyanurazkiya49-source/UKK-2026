<?php

// CEK SESSION
include "includes/cek_session.php";

// AMBIL DATA SESSION
$nama = $_SESSION['nama'] ?? $_SESSION['nama_lengkap'] ?? 'Pengguna';
$email = $_SESSION['email'] ?? '-';
$role = $_SESSION['role'] ?? '-';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Aplikasi Pelanggaran Siswa</title>
</head>

<body>

    <h1>Dashboard Aplikasi Pelanggaran Siswa</h1>

    <p>Selamat datang,</p>

    <strong><?php echo htmlspecialchars($nama); ?></strong>

    <p>Email: <?php echo htmlspecialchars($email); ?></p>

    <p>Role: <?php echo htmlspecialchars($role); ?></p>

    <hr>

    <?php if ($role === 'admin'): ?>

        <h3>Menu Admin</h3>

        <p><a href="kelola_guru.php">kelola guru</a></p>
        <p><a href="kelola_siswa.php">kelola siswa</a></p>
        <p><a href="kelola_kelas.php">kelola kelas</a></p>
        <p><a href="kelola_pelanggaran.php">kelola pelanggaran</a></p>

    <?php endif; ?>

    <h3>Menu Admin & Guru</h3>

    <p><a href="kelola_kelas.php">kelola kelas</a></p>
    <p><a href="kelola_guru.php">kelola guru</a></p>

    <hr>

    <p><a href="logout.php">Logout</a></p>

</body>
</html>