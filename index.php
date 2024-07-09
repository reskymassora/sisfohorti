<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

  <!-- App CSS -->
  <link id="theme-style" rel="stylesheet" href="assets/css/portal.css" />

  <link rel="shortcut icon" href="assets/images/logo-utama.jpeg">

</head>

<body>

  <!-- Logic code -->
  <?php
  require 'function.php';

  $daftarTanaman = tampil_data("SELECT * FROM dataTanaman LIMIT 10");
  ?>
  <!-- End Of Logic Code -->

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
      <img class="navbar-logo rounded-circle" src="assets/images/logo-utama.jpeg" alt="">

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav ml-auto">
          <a class="nav-link text-white" href="#">Home <span class="sr-only">(current)</span></a>
          <a class="nav-link text-white" href="#tabel_komoditas">Tabel Komoditas</a>
          <a class="btn btn-success text-white tombol" href="login.php">Login</a>
        </div>
      </div>
    </div>
  </nav>
  <!-- Navbaar end -->

  <!-- Jumbotron -->
  <div class="jumbotron jumbotron-fluid">
    <div class="container">
      <img class="logo-utama rounded-circle" src="assets/images/logo-utama-nobg.png" alt="logo-utama">
      <img class="logo-utama " src="assets/images/mimika-nobg-resize.png" alt="logo-utama">
      <h2 class="display-4">
        <div class="title">
          Simfoni Hortikultura
        </div>
        <div>
          Dinas Tanaman Pangan Hortikultura Dan Perkebunan <br> Kabupaten Mimika
        </div>
    </div>
  </div>
  <!-- Akhir Jumbotron -->

  <!-- section Tanaman -->
  <section>
    <div class="container">
      <h2 class="mb-4 text-center" id="tabel_komoditas">INFORMASI KOMODITAS</h2>
      <div class="table-responsive">
        <table class="table app-table-hover mb-0 text-left">
          <thead>
            <tr class="bg-success text-white">
              <th class="cell">No.</th>
              <th class="cell">Distrik</th>
              <th class="cell">Komoditas</th>
              <th class="cell">Luas <br> Tanam (HA)</th>
              <th class="cell">Luas <br> Panen (HA)</th>
              <th class="cell">Data <br> Produksi (KW)</th>
              <th class="cell">Harga Komoditi Tingkat <br> Petani</th>
            </tr>
          </thead>

          <tbody class="table-group-divider">
            <?php $i = 1; ?>
            <?php foreach ($daftarTanaman as $data) : ?>
              <tr>
                <td><?= $i; ?></td>
                <td><?= $data['distrik'] ?></td>
                <td><?= $data['komoditas'] ?></td>
                <td><?= $data['luasLahan'] ?></td>
                <td><?= $data['luasPanen'] ?></td>
                <td><?= $data['dataProduksi'] ?></td>
                <td><?= $data['hktppm'] ?></td>
              </tr>
              <?php $i++; ?>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="text-center mt-3">
        <button type="button" onclick="window.location.href='daftar_lengkap.php'" class="btn btn-success text-white">Selengkapnya >></button>
      </div>
    </div>
  </section>
  <!-- End of section tanaman -->

  <!-- Footer -->
  <footer class="bg-body-tertiary text-center mt-5">
    <!-- Copyright -->
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      © 2024 Copyright :
      <span class="text-body">Dinas Tanaman Pangan Hortikultura Dan Perkebunan Kabupaten Mimika</span>
    </div>
    <!-- Copyright -->
  </footer>

</body>

<!-- jQuery, Popper.js, and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</html>