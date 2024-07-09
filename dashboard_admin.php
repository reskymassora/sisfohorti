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
</head>

<body class="app">

  <?php

  $page = 1;



  $daftarTanaman = tampil_data("SELECT * FROM dataTanaman");

  $totalLuasTanam = total_luas_lahan("SELECT SUM(luasLahan) as totalLuasLahan FROM dataTanaman");

  $totalLuasPanen = total_luas_panen("SELECT SUM(luasPanen) as totalLuasPanen FROM dataTanaman");

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
                <div class="bg-white"><hr></div>
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
                        <th class="cell">No.</th>
                        <th class="cell">Distrik</th>
                        <th class="cell">Komoditas</th>
                        <th class="cell">Luas Tanam <br> (HA)</th>
                        <th class="cell">Luas Panen <br> (HA)</th>
                        <th class="cell">Data Produksi <br> (KW)</th>
                        <th class="cell">Harga Komoditi <br> Tingkat Petani</th>
                        <th class="cell">Tanggal</th>
                        <th class="cell">Tindakan</th>
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
                          <td class="cell"><?= $data['luasPanen'] ?></td>
                          <td class="cell"><?= $data['dataProduksi'] ?></td>
                          <td class="cell">Rp <?= $data['hktppm'] ?></td>
                          <td class="cell"><?= $data['tanggal'] ?></td>
                          <td class="cell">
                            <a class="btn app-btn-primary p-2" href="edit.php?id=<?= urlencode($data["id"]) ?>">Edit</a>
                            <a class="btn app-btn-secondary deleteButton p-2" href="delete.php?id=<?= urlencode($data['id']) ?>">Delete</a>
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
    document.querySelectorAll('.deleteButton').forEach(function(button) {
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
              <a class="btn app-btn-primary p-2" href="edit.php?id=${encodeURIComponent(item.id)}">Edit</a>
              <a class="btn app-btn-secondary deleteButton p-2" href="delete.php?id=${encodeURIComponent(item.id)}">Delete</a>
            </td>
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

  <!-- Charts JS -->
  <script src="assets/plugins/chart.js/chart.min.js"></script>
  <script src="assets/js/index-charts.js"></script>

  <!-- Page Specific JS -->
  <script src="assets/js/app.js"></script>
</body>

</html>