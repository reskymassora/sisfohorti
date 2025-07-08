<?php

require 'function.php';

// Mengatur header untuk mengekspor file ke format Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Data-horti.xls");
header("Cache-Control: max-age=0");

// Mengambil data dari database
$daftarTanaman = tampil_data("SELECT * FROM dataTanaman");

?>
<table id="main-table" border="1">
  <thead>
    <tr>
      <th>No.</th>
      <th>Distrik</th>
      <th>Komoditas</th>
      <th>Luas Tanam <br> (HA)</th>
      <th>Luas Panen <br> (HA)</th>
      <th>Data Produksi <br> (KW)</th>
      <th>Harga Komoditi Tingkat <br> Petani (Minggu)</th>
      <th>Tanggal</th>
    </tr>
  </thead>

  <tbody>
    <?php $i = 1; ?>
    <?php foreach ($daftarTanaman as $data) : ?>
      <tr>
        <td><?= $i; ?></td>
        <td><?= $data['distrik'] ?></td>
        <td><?= $data['komoditas'] ?></td>
        <td><?= $data['luasLahan'] ?></td>
        <td><?= $data['luasPanen'] ?></td>
        <td><?= $data['dataProduksi'] ?></td>
        <td>Rp <?= $data['hktppm'] ?></td>
        <td><?= $data['tanggal'] ?></td>
      </tr>
      <?php $i++; ?>
    <?php endforeach; ?>
  </tbody>
</table>