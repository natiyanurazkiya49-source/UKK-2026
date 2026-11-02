<?php
// export_excel.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Header untuk mengunduh file Excel
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=laporan_pelanggaran.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Ambil data pelanggaran siswa
$sql = "
    SELECT
        s.nis,
        s.nama AS nama_siswa,
        pk.nama AS nama_pelanggaran
    FROM t_pelanggaran_siswa ps
    JOIN t_siswa s
        ON ps.siswa_id = s.id
    JOIN t_pelanggaran p
        ON ps.pelanggaran_id = p.id
    LEFT JOIN t_pelanggaran_kategori pk
        ON p.pelanggaran_kategori_id = pk.id
    ORDER BY ps.id DESC
";

$data = mysqli_query($koneksi, $sql);

if (!$data) {
    die("Gagal mengambil data: " .
        htmlspecialchars(mysqli_error($koneksi), ENT_QUOTES, 'UTF-8'));
}

// Mulai membuat tabel Excel
echo '<table border="1">';
echo '<tr>';
echo '<th>No</th>';
echo '<th>NIS</th>';
echo '<th>Nama Siswa</th>';
echo '<th>Jenis Pelanggaran</th>';
echo '</tr>';

$no = 1;

while ($row = mysqli_fetch_assoc($data)) {
    echo '<tr>';

    echo '<td>' . $no++ . '</td>';

    echo '<td>' . htmlspecialchars(
        (string) $row['nis'],
        ENT_QUOTES,
        'UTF-8'
    ) . '</td>';

    echo '<td>' . htmlspecialchars(
        (string) $row['nama_siswa'],
        ENT_QUOTES,
        'UTF-8'
    ) . '</td>';

    echo '<td>' . htmlspecialchars(
        (string) ($row['nama_pelanggaran'] ?? '-'),
        ENT_QUOTES,
        'UTF-8'
    ) . '</td>';

    echo '</tr>';
}

echo '</table>';

mysqli_free_result($data);
exit;
?>