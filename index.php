<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

  <!-- App CSS -->
  <link id="theme-style" rel="stylesheet" href="assets/css/portal.css" />
  <style>
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


  <link rel="shortcut icon" href="assets/images/logo-utama.jpeg">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

  <?php

  require 'function.php';

  // insert data
  if (isset($_POST['submit_komentar'])) {
    global $conn;
    $nama = htmlspecialchars(trim($_POST['nama']));
    $komentar = htmlspecialchars(trim($_POST['komentar']));

    if (empty($nama) || empty($komentar)) {
      echo "<script>
      Swal.fire({
        title: 'Error!',
        text: 'Nama dan komentar tidak boleh kosong.',
        icon: 'error'
      }); 
    </script>";
    } else {
      $nama = $conn->real_escape_string($nama);
      $komentar = $conn->real_escape_string($komentar);

      $query = "INSERT INTO komentar (nama, komentar, tanggal) VALUES ('$nama', '$komentar', NOW())";
      if ($conn->query($query) === TRUE) {
        echo "<script>
        Swal.fire({
          title: 'Berhasil!',
          text: 'Komentar berhasil dikirim.',
          icon: 'success'
        }).then(() => {
          window.location = window.location.href;
        });
      </script>";
      } else {
        echo "<script>
        Swal.fire({
          title: 'Gagal!',
          text: 'Terjadi kesalahan saat menyimpan komentar.',
          icon: 'error'
        });
      </script>";
      }
    }
  }
  ?>

  <!-- Logic code -->
  <?php

  $daftarTanaman = tampil_data("SELECT * FROM datatanaman LIMIT 10");
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
          <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
          <a class="nav-link" href="grafik_harga_user.php">Grafik Harga <span class="sr-only">(current)</span></a>
          <a class="nav-link" href="#tabel_komoditas">Informasi Komoditas</a>
          <a class="btn text-white btn-success tombol" href="login.php">Login</a>
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
              <!--//table-responsive-->
            </div>
            <!--//app-card-body-->
          </div>
        </div>
      </div>
      <div class="text-center mt-3">
        <button type="button" onclick="window.location.href='daftar_lengkap.php'" class="btn btn-success text-white">Selengkapnya >></button>
      </div>
    </div>
  </section>

  <!-- Section Komentar -->
  <hr class="my-5"> <!-- Garis pemisah antar section -->
  <section style="background-color: #e8f5e9;" class="py-5">
    <div class="container mt-4">
      <!-- Form Komentar -->
      <div class="card p-4 mb-4 shadow-sm">
        <h4 class="mb-3">Tinggalkan Komentar</h4>

        <form method="post" id="formKomentar">
          <div class="form-group mb-3">
            <input type="text" name="nama" class="form-control" placeholder="Nama Anda" required>
          </div>
          <div class="form-group mb-3">
            <textarea name="komentar" class="form-control" rows="3" placeholder="Tulis komentar Anda di sini..." required></textarea>
          </div>
          <button type="submit" name="submit_komentar" class="btn btn-primary">Kirim Komentar</button>
        </form>


      </div>

      <!-- Tampilkan 5 Komentar Terbaru -->
      <div class="card p-4 shadow-sm">
        <h4 class="mb-3">Komentar Terbaru</h4>
        <?php
        global $conn;
        $query = "SELECT * FROM komentar ORDER BY tanggal DESC LIMIT 5";
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
          echo "<div class='card p-3 mb-2'>";
          echo "<strong>" . htmlspecialchars($row['nama']) . "</strong><br>";
          echo "<small>" . date('d M Y H:i', strtotime($row['tanggal'])) . "</small><br>";
          echo "<p>" . nl2br(htmlspecialchars($row['komentar'])) . "</p>";
          echo "</div>";
        }
        ?>

      </div>
    </div>
  </section>
  <!-- End of section komentar -->



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