<?php
session_start();

require_once 'function.php'; // Menggunakan require_once

function regenerateSession()
{
  if (!isset($_SESSION['last_regenerate'])) {
    $_SESSION['last_regenerate'] = time();
  } else if (time() - $_SESSION['last_regenerate'] > 300) { // 300 detik atau 5 menit
    session_regenerate_id(true);
    $_SESSION['last_regenerate'] = time();
  }
}

function enforceSessionTimeout()
{
  $timeout_duration = 1800; // 30 menit
  if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
  }
  $_SESSION['last_activity'] = time();
}

if (!isset($_SESSION['email'])) {
  header('Location: login.php');
  exit();
}

enforceSessionTimeout();
regenerateSession();

$email = $_SESSION['email'];
$userInfo = getUserByEmail($email);

if ($userInfo !== false) {
  $fullname = htmlspecialchars($userInfo['fullname'], ENT_QUOTES, 'UTF-8');
  $email = htmlspecialchars($userInfo['email'], ENT_QUOTES, 'UTF-8');
  $profilePhoto = htmlspecialchars($userInfo['fotoProfil'], ENT_QUOTES, 'UTF-8');
} else {
  echo "<p>Informasi pengguna tidak ditemukan.</p>";
  exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <title>Sistem Informasi Hortikultura</title>

  <style>
    #main-table {
      display: visible;
      /* Secara default, tampilkan tabel utama */
    }

    #main-table.hidden {
      display: none;
    }
  </style>

  <!-- Meta -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <meta name="description" content="Simfoni Hortikultura" />
  <meta name="author" content="Xiaoying Riley at 3rd Wave Media" />
  <link rel="shortcut icon" href="assets/images/logo-utama.jpeg">

  <!-- FontAwesome JS-->
  <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- App CSS -->
  <link id="theme-style" rel="stylesheet" href="assets/css/portal.css" />

  <style>
    .table {
      border-collapse: collapse;
      width: 100%;
    }

    .table th,
    .table td {
      border: 1px solid #ccc;
      /* border tipis */
      padding: 8px;
      text-align: center;
      white-space: nowrap;
      vertical-align: middle;
    }

    .table thead th {
      background-color: #f8f9fa;
      font-weight: bold;
    }

    .table-responsive {
      overflow-x: auto;
    }

    .table-responsive {
      overflow-x: auto;
    }

    .table th,
    .table td {
      white-space: nowrap;
      text-align: center;
      vertical-align: middle;
    }

    .table thead th {
      background-color: #f8f9fa;
    }
  </style>


</head>

<body class="app">

  <?php

  $page = 1;



  $daftarTanaman = tampil_data("SELECT * FROM dataTanaman");

  $totalLuasTanam = total_luas_lahan("SELECT SUM(luasLahan) as totalLuasLahan FROM dataTanaman");

  $totalLuasPanen = total_luas_panen("SELECT SUM(luasPanenHabisDiBongkar) as totalLuasPanen FROM dataTanaman");

  ?>

  <?php
  require 'navigation/header.php'
  ?>

  <div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
      <div class="container-xl">
        <h1 class="app-page-title">Dashboard</h1>
        <div class="row g-4 mb-4">

          <div class="col-6 col-lg-3">
            <div class="app-card app-card-stat shadow-sm h-100">
              <div class="app-card-body p-3 p-lg-4">
                <h4 class="stats-type mb-1">Total Luas Tanam (HA)</h4>
                <div class="stats-figure"> <?= $totalLuasTanam; ?></div>
              </div>
              <a class="app-card-link-mask" href="#"></a>
            </div>
          </div>

          <div class="col-6 col-lg-3">
            <div class="app-card app-card-stat shadow-sm h-100">
              <div class="app-card-body p-3 p-lg-4">
                <h4 class="stats-type mb-1">Total Luas Panen (HA)</h4>
                <div class="stats-figure"><?= $totalLuasPanen; ?></div>
              </div>
              <a class="app-card-link-mask" href="#"></a>
            </div>
          </div>

          <div class="col-6 col-lg-3">
            <div class=" bg-success app-card app-card-stat shadow-sm h-100 border border-success">
              <div class="app-card-body p-3 p-lg-4">
                <h4 class="stats-type mb-1 text-white">Total Produksi <br> Per Komoditi</h4>
                <div class="bg-white">
                  <hr>
                </div>
                <a class="btn app-btn-secondary deleteButton p-2">Tampilkan</a>
              </div>
              <a class="app-card-link-mask" href="totalProduksiKomoditi.php"></a>
            </div>
          </div>

        </div>
        <div class="app-search-box col mb-3">
          <form class="app-search-form" method="post">
            <input type="text" id="search" placeholder="Search... [ Nama distrik, Nama komoditas ]" name="keyword" class="form-control search-input" />
          </form>
        </div>

        <!--//row-->
        <div class="tab-content" id="orders-table-tab-content">
          <div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">
            <div class="app-card app-card-orders-table shadow-sm mb-5">
              <div class="app-card-body">


                <div id="results" class="tab-content table-responsive" id="orders-table-tab-content">
                  <!-- Tabel hasil pencarian akan dimasukkan di sini oleh JavaScript -->
                </div>

                <div class="table-responsive">
                  <!-- Tabel Utama -->
                  <table class="table app-table-hover mb-0 text-left" id="main-table">
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
                        <th rowspan="2">Tindakan</th>
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
                          <td class="cell">
                            <a class="btn app-btn-primary p-2" href="edit.php?id=<?= urlencode($data["id"]) ?>">
                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                              </svg>
                            </a>
                            <a class="btn app-btn-secondary deleteButton p-2" href="delete.php?id=<?= urlencode($data['id']) ?>">
                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                              </svg>
                            </a>
                          </td>
                        </tr>
                        <?php $i++; ?>
                      <?php endforeach; ?>
                    </tbody>
                  </table>

                </div>
                <!--//table-responsive-->
              </div>
              <!--//app-card-body-->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php
  require 'navigation/footer.php';
  ?>

  <!-- Javascript -->
  <script src="assets/plugins/popper.min.js"></script>
  <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

  <script>
    function addDeleteConfirmation(buttons) {
      buttons.forEach(function(button) {
        button.addEventListener('click', function(event) {
          event.preventDefault();
          const deleteUrl = this.href;

          Swal.fire({
            title: 'Yakin ingin menghapus data?',
            text: "Anda tidak akan dapat mengembalikan ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
          }).then((result) => {
            if (result.isConfirmed) {
              // Arahkan ke URL penghapusan jika pengguna mengonfirmasi
              window.location.href = deleteUrl;
            }
          });
          // Mengembalikan false untuk mencegah aksi default dari tautan
          return false;
        });
      });
    }

    // Inisialisasi event listener untuk tombol hapus pada halaman awal
    addDeleteConfirmation(document.querySelectorAll('.deleteButton'));

    //Search
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
          <th class="cell">Harga Komoditi Tingkat <br> Petani </th>
          <th class="cell">Tanggal</th>
          <th class="cell">Tindakan</th>
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
          <td class="cell">${item.tanggal}</td>
          <td class="cell">
            <a class="btn app-btn-primary p-2" href="edit.php?id=${encodeURIComponent(item.id)}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                </svg>
            </a>
            <a class="btn app-btn-secondary deleteButton p-2" href="delete.php?id=${encodeURIComponent(item.id)}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                  <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                </svg>
            </a>
          </td>
          `;
          tbody.appendChild(row);
        });
        table.appendChild(tbody);
        resultsDiv.appendChild(table);

        // Tambahkan event listener untuk tombol hapus pada hasil pencarian
        addDeleteConfirmation(document.querySelectorAll('.deleteButton'));
      } else {
        resultsDiv.innerHTML = '<p>No results found</p>';
      }
    }
  </script>


  <!-- Charts JS -->
  <script src="assets/plugins/chart.js/chart.min.js"></script>
  <script src="assets/js/index-charts.js"></script>

  <!-- Page Specific JS -->
  <script src="assets/js/app.js"></script>
</body>

</html>