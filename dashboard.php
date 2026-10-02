<?php
//dashboard.php
include 'includes/cek_session.php';
?>
<!DOCYTPE html>
<html>
    <head>
        <title>dashboard - Pelanggaran Siswa</title>
    <head>
    <body>
        <h1>selamat datang, <?php echo $_SESSION['name']; ?></h1>
        <p>anda login sebagai: <?php echo $_SESSION['role']; ?></p>


        <ul>
            <?php if($_SESSION['role'] == 'admin'){ ?>
        <li><a href="kelola_guru.php">kelola_guru</a></li>
        <li><a href="kelola_siswa.php">kelola_siswa</a></li>
        <li><a href="menu3.php">menu 3</a></li>
        <li><a href="menu4.php">menu 4</a></li>
    <?php } ?>

        <?php if ($_SESSION['role'] == 'guru'){ ?>
        <li><a href="menu3.php">menu 3</a></li>
        <li><a href="menu4.php">menu 4</a></li>
            <?php } ?>
</ul>
        <a href="logout.php">logout</a>
</body>
</html>