<?php
session_start();

if( $_SESSION != TRUE){
  header("Location: login.php");
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Sistem Informasi Hortikultura</title>

  <!-- Meta -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <meta name="description" content="Portal - Bootstrap 5 Admin Dashboard Template For Developers" />
  <meta name="author" content="Xiaoying Riley at 3rd Wave Media" />
  <link rel="shortcut icon" href="favicon.ico" />

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

    require 'function.php';

    $totalPotensiLahan = tampil_data("SELECT * FROM totalPotensiLahan");

  ?>


  <header class="app-header fixed-top">
    <div class="app-header-inner">
      <div class="container-fluid py-2">
        <div class="app-header-content">
          <div class="row justify-content-between align-items-center">
            <div class="col-auto">
              <a id="sidepanel-toggler" class="sidepanel-toggler d-inline-block d-xl-none" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" role="img">
                  <title>Menu</title>
                  <path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"></path>
                </svg>
              </a>
            </div>
            <!--//col-->
            <div class="search-mobile-trigger d-sm-none col">
              <i class="search-mobile-trigger-icon fa-solid fa-magnifying-glass"></i>
            </div>
            <!--//col-->
            <div class="app-search-box col">
              <form class="app-search-form">
                <input type="text" placeholder="Search... [ Nama distrik, Nama komoditas ]" name="search" class="form-control search-input" />
                <button type="submit" class="btn search-btn btn-primary" value="Search">
                  <i class="fa-solid fa-magnifying-glass"></i>
                </button>
              </form>
            </div>
            <!--//app-search-box-->

            <!--//app-utilities-->
          </div>
          <!--//row-->
        </div>
        <!--//app-header-content-->
      </div>
      <!--//container-fluid-->
    </div>
    <!--//app-header-inner-->
    <div id="app-sidepanel" class="app-sidepanel">
      <div id="sidepanel-drop" class="sidepanel-drop"></div>
      <div class="sidepanel-inner d-flex flex-column">
        <a href="#" id="sidepanel-close" class="sidepanel-close d-xl-none">&times;</a>
        <div class="app-branding">
          <a class="app-logo" href="index.php"><img class="logo-icon me-2" src="assets/images/app-logo.svg" alt="logo" /><span class="logo-text">Admin Page</span></a>
        </div>
        <!--//app-branding-->

        <!-- navbar -->
        <?php
          require 'navigation/sidebar.php'
        ?>

        <!--//app-sidepanel-footer-->
      </div>
      <!--//sidepanel-inner-->
    </div>
    <!--//app-sidepanel-->
  </header>
  <!--//app-header-->

  <div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
      <div class="container-xl">
        <h1 class="app-page-title">Total Potensi Lahan Per Komoditi</h1>
      
        <!--//row-->
        <div class="tab-content" id="orders-table-tab-content">
          <div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">
            <div class="app-card app-card-orders-table shadow-sm mb-5">
              <div class="app-card-body">
                <div class="table-responsive">
                  <table class="table app-table-hover mb-0 text-left">
                    <thead>
                      <tr>
                        <th class="cell">No.</th>
                        <th class="cell">Nama Komoditas</th>
                        <th class="cell">Total Potensi Lahan (HA)</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php $i = 1; ?>
                      <?php foreach ($totalPotensiLahan as $data) : ?>
                        <tr>
                          <td class="cell"><?= $i; ?></td>
                          <td class="cell"><?= $data['komoditas'] ?></td>
                          <td class="cell"><?= $data['totalPotensiLahan'] ?></td>
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

          <!--//tab-pane-->
        </div>
        <!--//row-->
      </div>
      <!--//container-fluid-->
    </div>
    <!--//app-content-->
  </div>
  <!--//app-wrapper-->

  <script>
    document.getElementById('deleteButton').addEventListener('click', function(event) {
      event.preventDefault(); // Mencegah tindakan default dari tautan
      const deleteUrl = this.href; // Simpan URL penghapusan

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
          // Jika pengguna mengkonfirmasi, arahkan ke URL penghapusan
          window.location.href = deleteUrl;
        }
      });
    });
  </script>

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