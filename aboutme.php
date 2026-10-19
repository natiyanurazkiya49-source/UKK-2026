<?php
session_start();
include "config/koneksi.php";

$nama = "Natiya Nur Azkiya";
$kelas = "XII RPL";
$sekolah = "SMK Muhammadiyah Tasikmalaya";
$jurusan = "Rekayasa Perangkat Lunak";
$hobi = "Mendengarkan Musik";
$cita_cita = "PRAMUGARI";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Saya - Profil</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #2d3748;
        }

        .container {
            width: 100%;
            max-width: 650px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.07);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        /* Header Card */
        .profile-header {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            padding: 40px 30px 30px;
            text-align: center;
            color: white;
            position: relative;
        }

        .avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background-color: #ffffff;
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            font-weight: 700;
            margin: 0 auto 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            border: 4px solid rgba(255, 255, 255, 0.3);
        }

        .profile-header h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .profile-header .tagline {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 500;
        }

        /* Body Content */
        .profile-body {
            padding: 30px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        @media (max-width: 480px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        .info-card {
            background: #f8fafc;
            padding: 15px 18px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .info-card.full-width {
            grid-column: span 2;
        }

        @media (max-width: 480px) {
            .info-card.full-width {
                grid-column: span 1;
            }
        }

        .info-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 15px;
            color: #1e293b;
            font-weight: 600;
        }

        .bio-section {
            background: #fff8f1;
            border-left: 4px solid #f97316;
            padding: 18px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .bio-section h3 {
            font-size: 16px;
            color: #c2410c;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .bio-section p {
            font-size: 14px;
            line-height: 1.6;
            color: #475569;
        }

        /* Action Button */
        .action-area {
            text-align: center;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 28px;
            background-color: #0f172a;
            color: #ffffff;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .btn-back:hover {
            background-color: #334155;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Header Profil -->
        <div class="profile-header">
            <div class="avatar">
                <?php echo strtoupper(substr($nama, 0, 1)); ?>
            </div>
            <h2><?php echo $nama; ?></h2>
            <p class="tagline"><?php echo $jurusan; ?> &bull; <?php echo $kelas; ?></p>
        </div>

        <!-- Detail Informasi -->
        <div class="profile-body">
            <div class="info-grid">
                <div class="info-card full-width">
                    <div class="info-label">Sekolah</div>
                    <div class="info-value"><?php echo $sekolah; ?></div>
                </div>

                <div class="info-card">
                    <div class="info-label">Kelas</div>
                    <div class="info-value"><?php echo $kelas; ?></div>
                </div>

                <div class="info-card">
                    <div class="info-label">Jurusan</div>
                    <div class="info-value"><?php echo $jurusan; ?></div>
                </div>

                <div class="info-card">
                    <div class="info-label">Hobi</div>
                    <div class="info-value"><?php echo $hobi; ?></div>
                </div>

                <div class="info-card">
                    <div class="info-label">Cita-cita</div>
                    <div class="info-value" style="color: #4f46e5;"><?php echo $cita_cita; ?></div>
                </div>
            </div>

            <!-- Deskripsi Diri -->
            <div class="bio-section">
                <h3>Deskripsi Diri</h3>
                <p>
                    Saya adalah seorang pelajar yang sedang belajar Rekayasa Perangkat Lunak. 
                    Saya senang mempelajari teknologi dan membuat website. Saya ingin terus 
                    belajar agar bisa berkembang dan meraih cita-cita saya.
                </p>
            </div>

            <!-- Tombol Navigasi -->
            <div class="action-area">
                <a href="dashboard.php" class="btn-back">
                    &larr; Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

</body>
</html>