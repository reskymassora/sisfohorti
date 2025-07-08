<?php
require 'function.php';

// Ambil data dari database
$daftarTanaman = tampil_data("SELECT * FROM dataTanaman");

// Header untuk file Excel
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Data-horti.xls");
header("Cache-Control: max-age=0");

// Hindari whitespace sebelum tag PHP
echo "\xEF\xBB\xBF"; // UTF-8 BOM agar Excel membaca karakter dengan benar

?>

<html>
<head>
  <meta charset="UTF-8">
</head>
<body>
  <table border="1">
    <thead>
      <tr>
        <th rowspan="2">No.</th>
        <th rowspan="2">Distrik</th>
        <th rowspan="2">Komoditas</th>
        <th rowspan="2">Luas Lahan (HA)</th>
        <th rowspan="2">Luas Tanam Akhir Bulan Lalu (HA)</th>
        <th colspan="2">Luas Panen (HA)</th>
        <th rowspan="2">Luas Rusak (HA)</th>
        <th rowspan="2">Luas Penanaman Baru (HA)</th>
        <th rowspan="2">Luas Tanaman Akhir Bulan Laporan (HA)</th>
        <th colspan="2">Data Produksi (KW)</th>
        <th rowspan="2">Harga Komoditi Tingkat Petani</th>
        <th rowspan="2">Tanggal</th>
      </tr>
      <tr>
        <th>Habis Dibongkar</th>
        <th>Belum Habis</th>
        <th>Dipanen Habis / Dibongkar</th>
        <th>Belum Habis</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; ?>
      <?php foreach ($daftarTanaman as $data): ?>
        <tr>
          <td><?= $i; ?></td>
          <td><?= $data['distrik'] ?></td>
          <td><?= $data['komoditas'] ?></td>
          <td><?= $data['luasLahan'] ?></td>
          <td><?= $data['luasTanamAkhirBulanLalu'] ?></td>
          <td><?= $data['luasPanenHabisDiBongkar'] ?></td>
          <td><?= $data['luasPanenBelumHabis'] ?></td>
          <td><?= $data['luasRusak'] ?></td>
          <td><?= $data['luasPenanamanBaru'] ?></td>
          <td><?= $data['luasTanamAkhirBulanLaporan'] ?></td>
          <td><?= $data['dataProduksiDiPanenHabis'] ?></td>
          <td><?= $data['dataProduksiBelumHabis'] ?></td>
          <td>Rp <?= $data['hktppm'] ?></td>
          <td><?= $data['tanggal'] ?></td>
        </tr>
        <?php $i++; ?>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
