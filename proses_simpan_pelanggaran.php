
<?php
// proses_simpan_pelanggaran.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// ========================================
// 1. VALIDASI REQUEST
// ========================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: catat_pelanggaran.php');
    exit;
}

// ========================================
// 2. AMBIL ID USER YANG LOGIN
// ========================================

$id_user = (int) (
    $_SESSION['id_user'] ??
    $_SESSION['id'] ??
    0
);

// ========================================
// 3. AMBIL DATA DARI FORM
// ========================================

$siswa_id = filter_input(INPUT_POST, 'siswa_id', FILTER_VALIDATE_INT);
$kelas_id = filter_input(INPUT_POST, 'kelas_id', FILTER_VALIDATE_INT);
$pelanggaran_id = filter_input(INPUT_POST, 'pelanggaran_id', FILTER_VALIDATE_INT);

$tanggal = trim($_POST['tanggal'] ?? '');
$keterangan = trim($_POST['keterangan'] ?? '');
$status = trim($_POST['status'] ?? '');

$status_diizinkan = [
    'Ditindaklanjuti',
    'Proses',
    'Selesai'
];

// ========================================
// 4. VALIDASI INPUT
// ========================================

if (
    $id_user <= 0 ||
    !$siswa_id ||
    !$kelas_id ||
    !$pelanggaran_id ||
    !in_array($status, $status_diizinkan, true)
) {
    $_SESSION['pesan_error'] =
        'Data tidak valid. Silakan periksa kembali form pelanggaran.';

    header('Location: catat_pelanggaran.php');
    exit;
}

// ========================================
// 5. VALIDASI TANGGAL
// ========================================

$format_tanggal = DateTime::createFromFormat('!Y-m-d', $tanggal);

if (
    !$format_tanggal ||
    $format_tanggal->format('Y-m-d') !== $tanggal
) {
    $_SESSION['pesan_error'] = 'Tanggal pelanggaran tidak valid.';
    header('Location: catat_pelanggaran.php');
    exit;
}

// ========================================
// 6. AMBIL TAHUN AJARAN AKTIF
// ========================================

$query_tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_tahun_ajaran
     WHERE status_aktif = 1
     ORDER BY id DESC
     LIMIT 1"
);

$tahun_aktif = $query_tahun
    ? mysqli_fetch_assoc($query_tahun)
    : null;

if (!$tahun_aktif) {
    $_SESSION['pesan_error'] =
        'Tahun ajaran aktif belum tersedia.';

    header('Location: catat_pelanggaran.php');
    exit;
}

// ========================================
// 7. AMBIL DATA GURU YANG LOGIN
// ========================================

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id, nama
     FROM t_guru
     WHERE user_id = ?
       AND status_aktif = 1
     LIMIT 1"
);

if (!$stmt) {
    $_SESSION['pesan_error'] =
        'Gagal mengambil data guru: ' . mysqli_error($koneksi);

    header('Location: catat_pelanggaran.php');
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);

$data_guru = mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmt)
);

mysqli_stmt_close($stmt);

if (!$data_guru) {
    $_SESSION['pesan_error'] =
        'Data guru tidak ditemukan. Pastikan user_id pada tabel t_guru sesuai dengan ID akun yang sedang login dan status guru aktif.';

    header('Location: catat_pelanggaran.php');
    exit;
}

// ========================================
// 8. AMBIL DATA SISWA SESUAI KELAS
// ========================================

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT s.id, s.nama
     FROM t_siswa s
     INNER JOIN t_kelas_siswa ks
        ON s.id = ks.siswa_id
     WHERE s.id = ?
       AND ks.kelas_id = ?
       AND s.status_aktif = 1
       AND ks.status_aktif = 1
     LIMIT 1"
);

if (!$stmt) {
    $_SESSION['pesan_error'] =
        'Gagal memeriksa data siswa: ' . mysqli_error($koneksi);

    header('Location: catat_pelanggaran.php');
    exit;
}

mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $siswa_id,
    $kelas_id
);

mysqli_stmt_execute($stmt);

$data_siswa = mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmt)
);

mysqli_stmt_close($stmt);

if (!$data_siswa) {
    $_SESSION['pesan_error'] =
        'Siswa tidak ditemukan, tidak aktif, atau tidak terdaftar pada kelas yang dipilih.';

    header('Location: catat_pelanggaran.php');
    exit;
}

// ========================================
// 9. PERIKSA KELAS AKTIF
// ========================================

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id, nama
     FROM t_kelas
     WHERE id = ?
       AND status_aktif = 1
     LIMIT 1"
);

if (!$stmt) {
    $_SESSION['pesan_error'] =
        'Gagal memeriksa kelas: ' . mysqli_error($koneksi);

    header('Location: catat_pelanggaran.php');
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $kelas_id);
mysqli_stmt_execute($stmt);

$data_kelas = mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmt)
);

mysqli_stmt_close($stmt);

if (!$data_kelas) {
    $_SESSION['pesan_error'] =
        'Data kelas tidak ditemukan atau tidak aktif.';

    header('Location: catat_pelanggaran.php');
    exit;
}

// ========================================
// 10. AMBIL JENIS PELANGGARAN
// ========================================

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id, nama, poin, pelanggaran_kategori_id
     FROM t_pelanggaran
     WHERE id = ?
       AND status_aktif = 1
     LIMIT 1"
);

if (!$stmt) {
    $_SESSION['pesan_error'] =
        'Gagal mengambil jenis pelanggaran: ' . mysqli_error($koneksi);

    header('Location: catat_pelanggaran.php');
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $pelanggaran_id);
mysqli_stmt_execute($stmt);

$data_pelanggaran = mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmt)
);

mysqli_stmt_close($stmt);

if (!$data_pelanggaran) {
    $_SESSION['pesan_error'] =
        'Jenis pelanggaran tidak ditemukan atau tidak aktif.';

    header('Location: catat_pelanggaran.php');
    exit;
}

// ========================================
// 11. SIAPKAN DATA PENYIMPANAN
// ========================================

$tahun_id = (int) $tahun_aktif['id'];

$kategori_id = (int) $data_pelanggaran['pelanggaran_kategori_id'];

$poin = (int) $data_pelanggaran['poin'];

$nama_siswa = $data_siswa['nama'];

$nama_kelas = $data_kelas['nama'];

$nama_pelanggaran = $data_pelanggaran['nama'];

$guru_id = (int) $data_guru['id'];

$nama_guru = $data_guru['nama'];

// Kolom tindakan dikosongkan
$tindakan = '';

// ========================================
// 12. SIMPAN DATA PELANGGARAN
// ========================================

$sql = "INSERT INTO t_pelanggaran_siswa (
            tahun_ajaran_id,
            siswa_id,
            nama_siswa,
            kelas_id,
            nama_kelas,
            pelanggaran_id,
            nama_pelanggaran,
            pelanggaran_kategori_id,
            guru_id,
            nama_guru,
            tanggal,
            keterangan,
            poin,
            tindakan,
            status
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    $_SESSION['pesan_error'] =
        'Gagal menyiapkan penyimpanan: ' . mysqli_error($koneksi);

    header('Location: catat_pelanggaran.php');
    exit;
}

// ========================================
// 13. MASUKKAN PARAMETER
// ========================================

mysqli_stmt_bind_param(
    $stmt,
    "iisisisiisssiss",
    $tahun_id,
    $siswa_id,
    $nama_siswa,
    $kelas_id,
    $nama_kelas,
    $pelanggaran_id,
    $nama_pelanggaran,
    $kategori_id,
    $guru_id,
    $nama_guru,
    $tanggal,
    $keterangan,
    $poin,
    $tindakan,
    $status
);

// ========================================
// 14. EKSEKUSI PENYIMPANAN
// ========================================

if (mysqli_stmt_execute($stmt)) {

    $_SESSION['pesan_sukses'] =
        'Data pelanggaran siswa berhasil disimpan.';

} else {

    $_SESSION['pesan_error'] =
        'Gagal menyimpan data pelanggaran: ' .
        mysqli_stmt_error($stmt);
}

mysqli_stmt_close($stmt);

// ========================================
// 15. KEMBALI KE HALAMAN CATAT PELANGGARAN
// ========================================

header('Location: catat_pelanggaran.php');
exit;

?>
