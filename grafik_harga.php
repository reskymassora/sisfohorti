<?php
session_start();
require 'function.php';
$page = 3;
// Cek apakah pengguna sudah login
if (!isset($_SESSION['email'])) {
  header('Location: login.php');
  exit();
}

$email = $_SESSION['email'];
$userInfo = getUserByEmail($email);

if ($userInfo !== false) {
  $fullname = $userInfo['fullname'];
  $email = $userInfo['email'];
  $profilePhoto = htmlspecialchars($userInfo['fotoProfil'], ENT_QUOTES, 'UTF-8');
} else {
  echo "<p>Informasi pengguna tidak ditemukan.</p>";
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
  <link rel="shortcut icon" href="assets/images/logo-utama.jpeg">

  <!-- FontAwesome JS-->
  <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

  <!-- Sweatalert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Chart -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- App CSS -->
  <link id="theme-style" rel="stylesheet" href="assets/css/portal.css" />

</head>

<body class="app">

  <?php

  $labels = [];
  $prices = [];
  $chartDataJson = '{}';

  if (isset($_POST['submit'])) {
    // Ambil data dari form
    $distrik = $_POST['distrik'] ?? '';
    $komoditas = $_POST['komoditas'] ?? '';
    $intervalWaktu = $_POST['intervalWaktu'] ?? '';
    $bulan = $_POST['bulan'] ?? '';
    $tahun = $_POST['tahun'] ?? '';

    // Validasi input
    if (empty($distrik) || empty($komoditas) || empty($intervalWaktu)) {
      echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Harap isi semua data',
            icon: 'error',
            confirmButtonColor: '#d42e1c',
        })
        </script>";;
    } else {
      // Buat koneksi database (pastikan $conn sudah didefinisikan sebelumnya)

      // Query SQL berdasarkan interval waktu yang dipilih
      $query = "";
      $params = [];

      if ($intervalWaktu == 'Minggu' && !empty($bulan) && !empty($tahun)) {
        $query = "SELECT hktppm AS prices, tanggal AS dates 
                      FROM datatanaman 
                      WHERE distrik = ? AND komoditas = ? 
                      AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
                      ORDER BY DAY(tanggal) ASC";
        $params = [$distrik, $komoditas, $bulan, $tahun];
      } elseif ($intervalWaktu == 'Bulan' && !empty($tahun)) {
        $query = "SELECT AVG(hktppm) AS prices, MONTH(tanggal) AS dates 
                      FROM dataTanaman 
                      WHERE distrik = ? AND komoditas = ? AND YEAR(tanggal) = ?
                      GROUP BY MONTH(tanggal) 
                      ORDER BY MONTH(tanggal) ASC";
        $params = [$distrik, $komoditas, $tahun];
      } elseif ($intervalWaktu == 'Tahun') {
        $query = "SELECT AVG(hktppm) AS prices, YEAR(tanggal) AS dates 
                      FROM dataTanaman 
                      WHERE distrik = ? AND komoditas = ?
                      GROUP BY YEAR(tanggal) 
                      ORDER BY YEAR(tanggal) ASC";
        $params = [$distrik, $komoditas];
      }

      if (!empty($query)) {
        $stmt = $conn->prepare($query);
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $labels[] = $row["dates"];
            $prices[] = $row["prices"];
          }
        }

        $stmt->close();
      } else {
        echo "
        <script>
        Swal.fire({
            title: 'Error!',
            text: 'Pilihan interval waktu tidak valid',
            icon: 'error',
            confirmButtonColor: '#d42e1c',
        })
        </script>";
      }

      // Ubah data menjadi format JSON untuk digunakan dalam JavaScript
      $chartData = [
        'labels' => $labels,
        'prices' => $prices
      ];
      $chartDataJson = json_encode($chartData);
    }
  }
  ?>

  <!-- Header -->
  <?php require 'navigation/header.php' ?>

  <div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
      <div class="container-xl">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
          <div class="col-auto">
            <h1 class="app-page-title mb-0">Grafik Harga</h1>
          </div>
          <div class="col-auto">
            <div class="page-utilities">
              <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                <div class="col-auto">
                </div>

              </div>
              <!--//row-->
            </div>
            <!--//table-utilities-->
          </div>
          <!--//col-auto-->
        </div>
        <div class="tab-content" id="orders-table-tab-content">
          <div class="tab-pane fade show active" role="tabpanel" aria-labelledby="orders-all-tab">
            <table cellpadding="10">

              <form class="app-search-form" method="post">

                <tr>
                  <td>
                    <select class="form-select" name="distrik" id="distrik">
                      <option value="">Nama Distrik</option>
                      <option value="MIMIKA BARAT">MIMIKA BARAT</option>
                      <option value="MIMIKA BARAT TENGAH">MIMIKA BARAT TENGAH</option>
                      <option value="MIMIKA BARAT JAUH">MIMIKA BARAT JAUH</option>
                      <option value="MIMIKA TIMUR">MIMIKA TIMUR</option>
                      <option value="MIMIKA TENGAH">MIMIKA TENGAH</option>
                      <option value="MIMIKA TIMUR JAUH">MIMIKA TIMUR JAUH</option>
                      <option value="MIMIKA BARU">MIMIKA BARU</option>
                      <option value="KUALA KENCANA">KUALA KENCANA</option>
                      <option value="TEMBAGAPURA">TEMBAGAPURA</option>
                      <option value="AGIMUGA">AGIMUGA</option>
                      <option value="JITA">JITA</option>
                      <option value="JILA">JILA</option>
                      <option value="IWAKA">IWAKA</option>
                      <option value="KWAMKI NARAMA">KWAMKI NARAMA</option>
                      <option value="WANIA">WANIA</option>
                      <option value="AMAR">AMAR</option>
                      <option value="ALAMA">ALAMA</option>
                      <option value="HOYA">HOYA</option>
                    </select>
                  </td>

                  <td>
                    <select class="form-select" name="bulan" id="bulan" disabled>
                      <option value="">Pilih Bulan</option>
                      <option value="01">Januari</option>
                      <option value="02">Februari</option>
                      <option value="03">Maret</option>
                      <option value="04">April</option>
                      <option value="05">Mei</option>
                      <option value="06">Juni</option>
                      <option value="07">Juli</option>
                      <option value="08">Agustus</option>
                      <option value="09">September</option>
                      <option value="10">Oktober</option>
                      <option value="11">November</option>
                      <option value="12">Desember</option>
                    </select>
                  </td>
                </tr>

                <tr>
                  <td>
                    <select class="form-select" name="komoditas" id="komoditas">
                      <option value="">Nama Komoditas</option>
                      <option value="ANGGUR">ANGGUR</option>
                      <option value="BUNCIS">BUNCIS</option>
                      <option value="KETIMUN">KETIMUN</option>
                      <option value="LABU SIAM">LABU SIAM</option>
                      <option value="KANGKUNG">KANGKUNG</option>
                      <option value="BAYAM">BAYAM</option>
                      <option value="MELON">MELON</option>
                      <option value="SEMANGKA">SEMANGKA</option>
                      <option value="BAWANG DAUN">BAWANG DAUN</option>
                      <option value="KUBIS">KUBIS</option>
                      <option value="PETSAI/SAWI">PETSAI/SAWI</option>
                      <option value="KACANG PANJANG">KACANG PANJANG</option>
                      <option value="CABAI BESAR">CABAI BESAR</option>
                      <option value="CABAI RAWIT">CABAI RAWIT</option>
                      <option value="TOMAT">TOMAT</option>
                      <option value="TERONG">TERONG</option>
                      <option value="MANGGA">MANGGA</option>
                      <option value="ALPUKAT">ALPUKAT</option>
                      <option value="NANAS">NANAS</option>
                      <option value="PEPAYA">PEPAYA</option>
                      <option value="PISANG">PISANG</option>
                      <option value="SALAK">SALAK</option>
                      <option value="JERUK MANIS">JERUK MANIS</option>
                      <option value="JERUK NIPIS">JERUK NIPIS</option>
                      <option value="RAMBUTAN">RAMBUTAN</option>
                      <option value="DURIAN">DURIAN</option>
                      <option value="NANGKA">NANGKA</option>
                      <option value="JAMBU BIJI">JAMBU BIJI</option>
                      <option value="JAMBU AIR">JAMBU AIR</option>
                    </select>
                  </td>

                  <td>
                    <select class="form-select" name="tahun" id="tahun" disabled>
                      <option value="">Pilih Tahun</option>
                      <option value="2024">2024</option>

                    </select>
                  </td>
                </tr>

                <tr>
                  <td>
                    <select class="form-select" name="intervalWaktu" id="intervalWaktu">
                      <option value="">Interval Waktu</option>
                      <option value="Minggu">Minggu</option>
                      <option value="Bulan">Bulan</option>
                      <option value="Tahun">Tahun</option>
                    </select>
                  </td>

                  <td>
                    <button name="submit" type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">Tampilkan</button>
                  </td>
                </tr>
              </form>
            </table>

            <!-- chart -->
            <div class="card flex-fill w-100 draggable">
              <div class="card-body py-3">
                <div class="chart chart-sm">
                  <div class="chartjs-size-monitor">
                    <div class="chartjs-size-monitor-expand">
                      <div class=""></div>
                    </div>
                    <div class="chartjs-size-monitor-shrink">
                      <div class=""></div>
                    </div>
                  </div>
                  <!-- Mengatur ukuran canvas -->
                  <canvas id="hktppmChart" style="display: block; width: 150px; height: 50px;"></canvas>
                </div>
              </div>
            </div>
            <!-- end of chart -->


          </div>
        </div>
        <!--//tab-pane-->
      </div>
      <!--//tab-content-->
    </div>
    <!--//container-fluid-->
  </div>

  </div>
  <!--//app-wrapper-->

  <!-- Script Chart -->
  <script>
    // Pastikan chartData tersedia (dikirim dari PHP)
    <?php if (isset($chartDataJson)): ?>
      var chartData = <?php echo $chartDataJson; ?>;

      const ctx = document.getElementById('hktppmChart').getContext('2d');
      new Chart(ctx, {
        type: 'bar', // Anda bisa mengubah ini menjadi 'bar' jika ingin diagram batang
        data: {
          labels: chartData.labels,
          datasets: [{
            label: 'Harga Komoditas',
            data: chartData.prices,
            borderColor: '#36A2EB',
            backgroundColor: '#9BD0F5',
            tension: 0.1
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'Harga'
              }
            },
            x: {
              title: {
                display: true,
                text: 'Tanggal/Periode'
              }
            }
          },
          plugins: {
            title: {
              display: true,
              text: 'Grafik Harga Komoditas'
            }
          }
        }
      });
    <?php endif; ?>
  </script>

  <!-- Script interval waktu -->
  <script>
    document.getElementById('intervalWaktu').addEventListener('change', function() {
      const bulanSelect = document.getElementById('bulan');
      const tahunSelect = document.getElementById('tahun');

      if (this.value === 'Minggu') {
        bulanSelect.disabled = false;
        tahunSelect.disabled = false;
      } else if (this.value === 'Bulan') {
        bulanSelect.disabled = true;
        tahunSelect.disabled = false;
      } else {
        bulanSelect.disabled = true;
        tahunSelect.disabled = true;
      }
    });
  </script>



  <!-- Javascript -->
  <script src="assets/plugins/popper.min.js"></script>
  <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

  <!-- Page Specific JS -->
  <script src="assets/js/app.js"></script>
</body>

</html>