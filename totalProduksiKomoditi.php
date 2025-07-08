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

  $totalProduksiKomoditi = tampil_data("SELECT * FROM totalProduksiKomoditi");

  ?>

  <?php
  require 'navigation/header.php'
  ?>

  <div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
      <div class="container-xl">
        <h1 class="app-page-title">Total Produksi Per Komoditi</h1>
        <div class="row g-4 mb-4">
        </div>

        <!-- <div class="app-search-box col mb-3">
          <form class="app-search-form border border-success" method="post">
            <input type="text" id="search" placeholder="Search... [ Nama komoditas ]" name="keyword" class="form-control search-input" />
          </form>
        </div> -->

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
                        <th class="cell">Daftar Komoditas</th>
                        <th class="cell">Total Data Produksi (KW)</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php $i = 1; ?>
                      <?php foreach ($totalProduksiKomoditi as $data) : ?>
                        <tr>
                          <td class="cell"><?= $i; ?></td>
                          <td class="cell"><?= $data['daftarKomoditas'] ?></td>
                          <td class="cell"><?= rtrim(rtrim($data['totalDataProduksi'], '0'), '.') ?></td>
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

  <!-- Charts JS -->
  <script src="assets/plugins/chart.js/chart.min.js"></script>
  <script src="assets/js/index-charts.js"></script>

  <!-- Page Specific JS -->
  <script src="assets/js/app.js"></script>
</body>

</html>