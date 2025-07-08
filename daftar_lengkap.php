<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

  <!-- App CSS -->
  <link id="theme-style" rel="stylesheet" href="assets/css/portal.css" />

  <link rel="shortcut icon" href="assets/images/logo-utama.jpeg">

  <style>
    #main-table {
      display: visible;
      /* Secara default, tampilkan tabel utama */
    }

    #main-table.hidden {
      display: none;
    }

    .table-responsive {
      overflow-x: auto;
      white-space: nowrap;
    }

    #main-table th,
    #main-table td {
      white-space: nowrap;
      /* Jangan biarkan teks pindah baris */
    }

    #main-table {
      min-width: max-content;
      /* Pastikan tabel tidak memaksa wrap */
    }

    #main-table thead th[colspan] {
      text-align: center;
      vertical-align: middle;
    }
  </style>

</head>

<body class="container">

  <?php

  require 'function.php';

  $page = 1;

  $daftarTanaman = tampil_data("SELECT * FROM datatanaman");

  ?>

  <div>
    <h2 class="mb-3 text-center mt-4" id="tabel_komoditas">INFORMASI KOMODITAS</h2>
  </div>

  <form class="app-search-form" method="post">
    <input type="text" id="search" placeholder="Search... [ Nama distrik, Nama komoditas ]" name="keyword" class="form-control search-input" />
  </form>

  <!-- Button Cetak PDF -->
  <a class="btn btn-danger text-white mt-3" href="laporanpdf.php" target="_blank">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down" viewBox="0 0 16 16">
      <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293z" />
      <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z" />
    </svg>
    Download PDF
  </a>

  <!-- Button cetak Excel -->
  <a class="btn btn-success text-white mt-3" href="laporanexcel.php" target="_blank">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down" viewBox="0 0 16 16">
      <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293z" />
      <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z" />
    </svg>
    Download Excel
  </a>


  <!--//row-->
  <div class="tab-content mt-3" id="orders-table-tab-content">
    <div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">
      <div class="app-card app-card-orders-table shadow-sm mb-5">
        <div class="app-card-body">
          <div id="results" class="tab-content table-responsive" id="orders-table-tab-content">
            <!-- Tabel hasil pencarian akan dimasukkan di sini oleh JavaScript -->
          </div>
          <div class="table-responsive">
            <table class="table app-table-hover mb-0 text-left" id="main-table">
              <thead>
                <tr>
                  <th rowspan="2">No.</th>
                  <th rowspan="2">Distrik</th>
                  <th rowspan="2">Komoditas</th>
                  <th rowspan="2">Luas Lahan (HA)</th>
                  <th rowspan="2">Luas Tanam Akhir Bulan Lalu (HA)</th>
                  <th colspan="2" style="text-align: center;">Luas Panen (HA)</th>
                  <th rowspan="2">Luas Rusak (HA)</th>
                  <th rowspan="2">Luas Penanaman Baru (HA)</th>
                  <th rowspan="2">Luas Tanaman Akhir Bulan Laporan (HA)</th>
                  <th colspan="2" style="text-align: center;">Data Produksi (KW)</th>
                  <th rowspan="2">Harga Komoditi Tingkat Petani</th>
                  <th rowspan="2">Tanggal</th>
                </tr>
                <tr>
                  <th>Habis Dibongkar (HA)</th>
                  <th>Belum Habis (HA)</th>
                  <th>Dipanen Habis / Dibongkar (HA)</th>
                  <th>Belum Habis (HA)</th>
                </tr>
              </thead>



              <tbody>
                <?php $i = 1; ?>
                <?php foreach ($daftarTanaman as $data) : ?>
                  <tr>
                    <td class="cell"><?= $i; ?></td>
                    <td class="cell"><?= $data['distrik'] ?></td>
                    <td class="cell"><?= $data['komoditas'] ?></td>
                    <td class="cell"><?= $data['luasLahan'] ?></td>
                    <td class="cell"><?= $data['luasTanamAkhirBulanLalu'] ?></td>
                    <td class="cell"><?= $data['luasPanenHabisDiBongkar'] ?></td>
                    <td class="cell"><?= $data['luasPanenBelumHabis'] ?></td>
                    <td class="cell"><?= $data['luasRusak'] ?></td>
                    <td class="cell"><?= $data['luasPenanamanBaru'] ?></td>
                    <td class="cell"><?= $data['luasTanamAkhirBulanLaporan'] ?></td>
                    <td class="cell"><?= $data['dataProduksiDiPanenHabis'] ?></td>
                    <td class="cell"><?= $data['dataProduksiBelumHabis'] ?></td>
                    <td class="cell">Rp <?= $data['hktppm'] ?></td>
                    <td class="cell"><?= $data['tanggal'] ?></td>
                  </tr>
                  <?php $i++; ?>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>


  <script>
    // Fungsi untuk menangani pencarian
    function handleSearch() {
      const query = document.getElementById('search').value;
      const mainTable = document.getElementById('main-table');
      const resultsDiv = document.getElementById('results');

      if (query.length > 2) { // Memulai pencarian jika input lebih dari 2 karakter
        mainTable.classList.add('hidden'); // Sembunyikan tabel utama
        mainTable.classList.remove('visible');
        fetch(`function.php?action=search&q=${encodeURIComponent(query)}`)
          .then(response => response.json())
          .then(data => {
            displayResults(data);
          })
          .catch(error => console.error('Error:', error));
      } else {
        mainTable.classList.remove('hidden'); // Tampilkan kembali tabel utama
        mainTable.classList.add('visible');
        resultsDiv.innerHTML = ''; // Bersihkan hasil pencarian
      }
    }

    // Event listener untuk input pencarian
    document.getElementById('search').addEventListener('input', handleSearch);

    // Fungsi untuk menampilkan hasil pencarian
    function displayResults(data) {
      const resultsDiv = document.getElementById('results');
      resultsDiv.innerHTML = '';

      if (data.length > 0) {
        const table = document.createElement('table');
        table.className = 'table app-table-hover mb-0 text-left';

        const thead = document.createElement('thead');
        thead.innerHTML = `
        <tr>
          <th class="cell">No.</th>
          <th class="cell">Distrik</th>
          <th class="cell">Komoditas</th>
          <th class="cell">Luas Tanam <br> (HA)</th>
          <th class="cell">Luas Panen <br> (HA)</th>
          <th class="cell">Data Produksi <br> (KW)</th>
          <th class="cell">Harga Komoditi Tingkat <br> Petani (Minggu)</th>
        </tr>
      `;
        table.appendChild(thead);

        const tbody = document.createElement('tbody');

        data.forEach((item, index) => {
          const row = document.createElement('tr');
          row.innerHTML = `
          <td class="cell">${index + 1}</td>
          <td class="cell">${item.distrik}</td>
          <td class="cell">${item.komoditas}</td>
          <td class="cell">${item.luasLahan}</td>
          <td class="cell">${item.luasPanen}</td>
          <td class="cell">${item.dataProduksi}</td>
          <td class="cell">Rp ${item.hktppm}</td>
        `;
          tbody.appendChild(row);
        });

        table.appendChild(tbody);
        resultsDiv.appendChild(table);
      } else {
        resultsDiv.innerHTML = '<p>No results found</p>';
      }
    }
  </script>

</body>



</html>