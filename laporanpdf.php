<?php

require_once __DIR__ . '/vendor/autoload.php';
require 'function.php';

// Mengambil data dari database
$daftarTanaman = tampil_data("SELECT * FROM dataTanaman");

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

// Mengatur header tabel
$pdf->SetFont('helvetica', 'B', 10);
$html = '<table border="0.5" cellpadding="4" cellspacing="0" style="border-collapse: collapse;">
            <thead>
                <tr style="border: 0.5px solid black;">
                    <th width="30">No.</th> <!-- Lebar kolom nomor diatur lebih kecil -->
                    <th>Distrik</th>
                    <th>Komoditas</th>
                    <th>Luas Tanam <br> (HA)</th>
                    <th>Luas Panen <br> (HA)</th>
                    <th>Data Produksi <br> (KW)</th>
                    <th>Harga Komoditi Tingkat <br> Petani (Minggu)</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>';

// Menambah data ke dalam tabel
$i = 1;
foreach ($daftarTanaman as $data) {
    $html .= '<tr style="border: 0.5px solid black;">
                <td style="text-align: center; width: 30px;">' . $i . '</td> <!-- Lebar kolom nomor diatur lebih kecil -->
                <td>' . $data['distrik'] . '</td>
                <td>' . $data['komoditas'] . '</td>
                <td>' . $data['luasLahan'] . '</td>
                <td>' . $data['luasPanen'] . '</td>
                <td>' . $data['dataProduksi'] . '</td>
                <td>Rp ' . $data['hktppm'] . '</td>
                <td>' . $data['tanggal'] . '</td>
              </tr>';
    $i++;
}


$html .= '</tbody></table>';

// Menulis konten HTML ke dalam PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Menutup dan mengeluarkan PDF
$pdf->Output('Data-horti.pdf', 'D');

?>
