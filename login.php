
<?php
session_start();

// Jika sudah login, langsung ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Aplikasi Pelanggaran Siswa</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #101d3a, #1e3c72, #315b96);
        }

        .login-container {
            width: 100%;
            max-width: 430px;
            padding: 20px;
        }

        .login-card {
            background: #fff;
            padding: 40px 35px;
            border-radius: 18px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.25);
        }

        .logo {
            width: 75px;
            height: 75px;
            background: #e8efff;
            color: #234b87;
            border-radius: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 32px;
            margin: 0 auto 20px;
        }

        .login-card h3 {
            text-align: center;
            font-weight: 700;
            color: #1c3154;
            margin-bottom: 8px;
        }

        .subtitle {
            text-align: center;
            color: #8993a4;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .form-group label {
            color: #344563;
            font-weight: 600;
            font-size: 14px;
        }

        .form-control {
            height: 48px;
            border: 1px solid #dce3ee;
            border-radius: 9px;
            font-size: 14px;
            padding-left: 15px;
        }

        .form-control:focus {
            border-color: #315b96;
            box-shadow: 0 0 0 3px rgba(49,91,150,0.12);
        }

        .btn-login {
            width: 100%;
            height: 48px;
            border: none;
            border-radius: 9px;
            background: linear-gradient(135deg, #1e3c72, #315b96);
            color: white;
            font-weight: 600;
            font-size: 15px;
            margin-top: 10px;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: #142c54;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(30,60,114,0.25);
            color: white;
        }

        .error-message {
            background: #fff0f0;
            color: #d63031;
            border-radius: 8px;
            padding: 11px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .footer-text {
            text-align: center;
            font-size: 12px;
            color: #9aa3b2;
            margin-top: 25px;
        }

        .input-icon {
            color: #8993a4;
            margin-right: 7px;
        }
    </style>
</head>

<body>

<div class="login-container">

    <div class="login-card">

        <div class="logo">
            <i class="fas fa-user-shield"></i>
        </div>

        <h3>Selamat Datang!</h3>

        <p class="subtitle">
            Aplikasi Sistem Informasi Pelanggaran Siswa
        </p>

        <?php
        if (isset($_SESSION['pesan_error'])) {
            echo '<div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> '
                    . htmlspecialchars($_SESSION['pesan_error']) .
                  '</div>';

            unset($_SESSION['pesan_error']);
        }
        ?>

        <form action="proses_login.php" method="POST">

            <div class="form-group">
                <label>
                    <i class="fas fa-envelope input-icon"></i>
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Masukkan email Anda"
                    required>
            </div>

            <div class="form-group">
                <label>
                    <i class="fas fa-lock input-icon"></i>
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Masukkan password"
                    required>
            </div>

            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt"></i>
                Login Sekarang
            </button>

        </form>

        <div class="footer-text">
            &copy; 2026 Aplikasi Pelanggaran Siswa
            <br>
            Sistem Informasi Sekolah
        </div>

    </div>

</div>

</body>
</html>
