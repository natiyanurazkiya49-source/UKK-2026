
<?php
session_start();
include "config/koneksi.php";

$nama = "Natiya Nur Azkiya";
$kelas = "XII RPL";
$sekolah = "SMK Muhammadiyah Tasikmalaya";
$jurusan = "Rekayasa Perangkat Lunak";
$hobi = "Mendengarkan Musik";
$cita_cita = "PRAMUGARI";

$foto = "assets/foto-profil.jpg";
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Profile</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: #f5f1eb;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 25px;
}

/* Card */
.profile-card {
    width: 100%;
    max-width: 750px;
    background: white;
    border-radius: 18px;
    padding: 35px;
    box-shadow: 0 8px 30px rgba(80, 60, 40, 0.08);
}

/* Profile Header */
.profile-header {
    display: flex;
    align-items: center;
    gap: 25px;
    padding-bottom: 25px;
    border-bottom: 1px solid #eee9e2;
}

.profile-header img {
    width: 115px;
    height: 115px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #eee5d9;
}

.profile-name h2 {
    font-size: 23px;
    color: #44382e;
    margin-bottom: 7px;
}

.profile-name p {
    color: #9b8877;
    font-size: 13px;
}

.status {
    display: inline-block;
    background: #f3eee6;
    color: #8a725b;
    padding: 6px 13px;
    border-radius: 20px;
    font-size: 11px;
    margin-top: 10px;
}

/* Content */
.content {
    padding-top: 25px;
}

.title {
    font-size: 16px;
    color: #44382e;
    margin-bottom: 18px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 13px;
}

.info-item {
    padding: 15px;
    background: #faf8f5;
    border-radius: 10px;
    border: 1px solid #f0ebe4;
}

.info-item span {
    display: block;
    font-size: 11px;
    color: #a18e7b;
    margin-bottom: 5px;
}

.info-item strong {
    font-size: 13px;
    color: #514337;
    font-weight: 600;
}

/* Description */
.description {
    margin-top: 25px;
    padding: 20px;
    background: #f8f4ee;
    border-radius: 12px;
}

.description h3 {
    font-size: 15px;
    color: #514337;
    margin-bottom: 9px;
}

.description p {
    font-size: 12px;
    color: #897969;
    line-height: 1.8;
}

/* Dream */
.dream {
    margin-top: 15px;
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 16px;
    border: 1px solid #eee6dc;
    border-radius: 12px;
}

.dream i {
    font-size: 23px;
    color: #ad8b68;
}

.dream span {
    display: block;
    font-size: 11px;
    color: #a18e7b;
}

.dream strong {
    font-size: 14px;
    color: #514337;
}

/* Button */
.button-area {
    margin-top: 25px;
    text-align: center;
}

.btn-back {
    display: inline-block;
    padding: 11px 25px;
    background: #78634f;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 500;
    transition: 0.3s;
}

.btn-back:hover {
    background: #574532;
    color: white;
    text-decoration: none;
}

/* Responsive */
@media(max-width: 550px) {
    .profile-card {
        padding: 23px;
    }

    .profile-header {
        flex-direction: column;
        text-align: center;
    }

    .profile-name h2 {
        font-size: 20px;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }
}

</style>
</head>

<body>

<div class="profile-card">

    <!-- Foto dan Nama -->
    <div class="profile-header">

        <img
            src="<?php echo htmlspecialchars($foto); ?>"
            alt="Foto Profil"
            onerror="this.src='img/natiya.jpg'">
        <div class="profile-name">
            <h2><?php echo htmlspecialchars($nama); ?></h2>

            <p><?php echo htmlspecialchars($jurusan); ?></p>

            <div class="status">
                <i class="fas fa-graduation-cap"></i>
                Pelajar <?php echo htmlspecialchars($kelas); ?>
            </div>
        </div>

    </div>

    <!-- Informasi -->
    <div class="content">

        <h3 class="title">Informasi Pribadi</h3>

        <div class="info-grid">

            <div class="info-item">
                <span>Nama Lengkap</span>
                <strong><?php echo htmlspecialchars($nama); ?></strong>
            </div>

            <div class="info-item">
                <span>Sekolah</span>
                <strong><?php echo htmlspecialchars($sekolah); ?></strong>
            </div>

            <div class="info-item">
                <span>Kelas</span>
                <strong><?php echo htmlspecialchars($kelas); ?></strong>
            </div>

            <div class="info-item">
                <span>Jurusan</span>
                <strong><?php echo htmlspecialchars($jurusan); ?></strong>
            </div>

            <div class="info-item">
                <span>Hobi</span>
                <strong><?php echo htmlspecialchars($hobi); ?></strong>
            </div>

            <div class="info-item">
                <span>Cita-cita</span>
                <strong><?php echo htmlspecialchars($cita_cita); ?></strong>
            </div>

        </div>

        <!-- Tentang Saya -->
        <div class="description">

            <h3>Tentang Saya</h3>

            <p>
                Saya merupakan pelajar jurusan Rekayasa Perangkat Lunak
                yang memiliki semangat untuk belajar dan mengembangkan
                kemampuan di bidang teknologi. Saya senang mempelajari
                hal baru dan ingin terus berkembang untuk meraih cita-cita.
            </p>

        </div>

        <!-- Cita-cita -->
        <div class="dream">

            <i class="fas fa-plane"></i>

            <div>
                <span>My Future Dream</span>
                <strong><?php echo htmlspecialchars($cita_cita); ?></strong>
            </div>

        </div>

        <!-- Tombol -->
        <div class="button-area">

            <a href="dashboard.php" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Dashboard
            </a>

        </div>

    </div>

</div>

</body>
</html>
