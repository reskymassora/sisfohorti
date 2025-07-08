<?php

require_once __DIR__ . '/vendor/autoload.php';
require 'function.php';

// Ambil data dari database
$daftarTanaman = tampil_data("SELECT * FROM datatanaman");

// Membuat instance TCPDF (dengan orientasi Landscape)
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

// Nonaktifkan header dan footer default
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Informasi dokumen
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Author');
$pdf->SetTitle('Data Horti');
$pdf->SetSubject('Export Data Horti');
$pdf->SetKeywords('TCPDF, PDF, export, hortikultura');

// Margin dan halaman
$pdf->SetMargins(10, 10, 10);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);
$pdf->AddPage();

// Judul halaman
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Informasi Komoditas Hortikultura', 0, 1, 'C');
$pdf->Ln(5);

// Set font isi
$pdf->SetFont('helvetica', '', 10);

// HTML tabel
$html = '
<style>
  table {
    border-collapse: collapse;
    width: 100%;
    font-size: 12px;
  }
  th, td {
    border: 0.3px solid #000;
    padding: 6px;
    text-align: center;
    vertical-align: middle;
  }
  th {
    font-weight: bold;
    background-color: #f0f0f0;
  }
</style>

<table>
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
  <tbody>';

// Menambah isi data
$i = 1;
foreach ($daftarTanaman as $data) {
    $html .= '
    <tr>
      <td>' . $i . '</td>
      <td>' . $data['distrik'] . '</td>
      <td>' . $data['komoditas'] . '</td>
      <td>' . $data['luasLahan'] . '</td>
      <td>' . $data['luasTanamAkhirBulanLalu'] . '</td>
      <td>' . $data['luasPanenHabisDiBongkar'] . '</td>
      <td>' . $data['luasPanenBelumHabis'] . '</td>
      <td>' . $data['luasRusak'] . '</td>
      <td>' . $data['luasPenanamanBaru'] . '</td>
      <td>' . $data['luasTanamAkhirBulanLaporan'] . '</td>
      <td>' . $data['dataProduksiDiPanenHabis'] . '</td>
      <td>' . $data['dataProduksiBelumHabis'] . '</td>
      <td>Rp ' . $data['hktppm'] . '</td>
      <td>' . $data['tanggal'] . '</td>
    </tr>';
    $i++;
}

$html .= '</tbody></table>';

// Tulis ke PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Output file PDF
$pdf->Output('Data-horti.pdf', 'D'); // D = Download langsung

?>
