<?php

require_once __DIR__ . '/vendor/autoload.php';
require 'function.php';

// Mengambil data dari database
$daftarTanaman = tampil_data("SELECT * FROM datatanaman");

// Membuat instance TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Mengatur informasi dokumen
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Author');
$pdf->SetTitle('Data Horti');
$pdf->SetSubject('Export Data Horti');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// Mengatur margin
$pdf->SetMargins(10, 10, 10);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);

// Menambah halaman
$pdf->AddPage();

// Mengatur judul
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Informasi Komoditas', 0, 1, 'C');
$pdf->Ln(5);

// Mengatur header tabel PDF
$pdf->SetFont('helvetica', 'B', 10);
$html = '<table border="0.5" cellpadding="4" cellspacing="0" style="border-collapse: collapse; font-size:10px;">
            <thead>
                <tr style="text-align:center; font-weight: bold;">
                    <th rowspan="2" width="25">No.</th>
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
                <tr style="text-align:center; font-weight: bold;">
                    <th>Habis Dibongkar</th>
                    <th>Belum Habis</th>
                    <th>Dipanen Habis / Dibongkar</th>
                    <th>Belum Habis</th>
                </tr>
            </thead>
            <tbody>';

// Menambah data ke dalam tabel PDF
$i = 1;
foreach ($daftarTanaman as $data) {
    $html .= '<tr style="border: 0.5px solid black;">
                <td style="text-align: center;">' . $i . '</td>
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

// Output ke PDF
$pdf->writeHTML($html, true, false, true, false, '');


$html .= '</tbody></table>';

// Menulis konten HTML ke dalam PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Menutup dan mengeluarkan PDF
$pdf->Output('Data-horti.pdf', 'D');

?>
