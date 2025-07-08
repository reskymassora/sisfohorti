<?php
session_start();

require 'function.php';

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

  <!-- App CSS -->
  <link id="theme-style" rel="stylesheet" href="assets/css/portal.css" />
</head>

<body class="app">

  <!--Logic Code -->
  <?php
  $page = 2;


  // insert data
  if (isset($_POST['submit'])) {

    // Cek inputan kosong atau tidak
    if (
      empty($_POST['distrik']) ||
      empty($_POST['komoditas']) ||
      empty($_POST['luasLahan']) ||
      empty($_POST['luasTanamAkhirBulanLalu']) ||
      empty($_POST['luasPanenHabisDiBongkar']) ||
      empty($_POST['luasPanenBelumHabis']) ||
      empty($_POST['luasRusak']) ||
      empty($_POST['luasPenanamanBaru']) ||
      empty($_POST['luasTanamAkhirBulanLaporan']) ||
      empty($_POST['dataProduksiDiPanenHabis']) ||
      empty($_POST['dataProduksiBelumHabis']) ||
      empty($_POST['hktppm']) ||
      empty($_POST['tanggal'])
    ) {
      echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Data tidak boleh kosong',
            icon: 'error',
            confirmButtonColor: '#d42e1c',
        })
        </script>";
    } else {
      // Ambil data dari form
      $distrik = strtoupper(htmlspecialchars($_POST['distrik'], ENT_QUOTES, 'UTF-8'));
      $komoditas = strtoupper(htmlspecialchars($_POST['komoditas'], ENT_QUOTES, 'UTF-8'));
      $luasLahan = htmlspecialchars($_POST['luasLahan'], ENT_QUOTES, 'UTF-8');
      $luasTanamAkhirBulanLalu = strtoupper(htmlspecialchars($_POST['luasTanamAkhirBulanLalu'], ENT_QUOTES, 'UTF-8'));
      $luasPanenHabisDiBongkar = htmlspecialchars($_POST['luasPanenHabisDiBongkar'], ENT_QUOTES, 'UTF-8');
      $luasPanenBelumHabis = strtoupper(htmlspecialchars($_POST['luasPanenBelumHabis'], ENT_QUOTES, 'UTF-8'));
      $luasRusak = strtoupper(htmlspecialchars($_POST['luasRusak'], ENT_QUOTES, 'UTF-8'));
      $luasPenanamanBaru = strtoupper(htmlspecialchars($_POST['luasPenanamanBaru'], ENT_QUOTES, 'UTF-8'));
      $luasTanamAkhirBulanLaporan = strtoupper(htmlspecialchars($_POST['luasTanamAkhirBulanLaporan'], ENT_QUOTES, 'UTF-8'));
      $dataProduksiDiPanenHabis = htmlspecialchars($_POST['dataProduksiDiPanenHabis'], ENT_QUOTES, 'UTF-8');
      $dataProduksiBelumHabis = htmlspecialchars($_POST['dataProduksiBelumHabis'], ENT_QUOTES, 'UTF-8');
      $hktppm = htmlspecialchars($_POST['hktppm'], ENT_QUOTES, 'UTF-8');
      $tanggal = htmlspecialchars($_POST['tanggal'], ENT_QUOTES, 'UTF-8');

      // Ubah format tanggal
      $date = new DateTime($tanggal);
      $formatted_date = $date->format('d F Y'); // Format menjadi tanggal yang sebenarnya, misal: 12 Januari 2024

      // Gantikan nama bulan dengan bahasa Indonesia
      $months = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
      ];

      // Query untuk memasukkan data, tanpa menyertakan kolom id
      $query = "INSERT INTO dataTanaman (
                distrik, 
                komoditas, 
                luasLahan, 
                luasTanamAkhirBulanLalu, 
                luasPanenHabisDiBongkar, 
                luasPanenBelumHabis, 
                luasRusak, 
                luasPenanamanBaru, 
                luasTanamAkhirBulanLaporan, 
                dataProduksiDiPanenHabis, 
                dataProduksiBelumHabis, 
                hktppm,
                tanggal ) 
                  VALUES (
                    '$distrik', 
                    '$komoditas',
                    '$luasLahan', 
                    '$luasTanamAkhirBulanLalu', 
                    '$luasPanenHabisDiBongkar', 
                    '$luasPanenBelumHabis', 
                    '$luasRusak', 
                    '$luasPenanamanBaru', 
                    '$luasTanamAkhirBulanLaporan',
                    '$dataProduksiDiPanenHabis', 
                    '$dataProduksiBelumHabis', 
                    '$hktppm', 
                    '$tanggal')";

      // Eksekusi query
      if ($conn->query($query) === TRUE) {
        echo "<script>
                      Swal.fire({
                        title: 'Sukses!',
                        text: 'Data berhasil disimpan.',
                        icon: 'success',
                        confirmButtonColor: '#069118',
                      }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = 'dashboard_admin.php';
                        }
                      });
                    </script>";
      } else {
        echo "
            <script>
            Swal.fire({
              title: 'Error!',
              text: 'Data gagal disimpan.',
              icon: 'error',
              confirmButtonText: 'Coba lagi'
            });
          </script>";
      }
    }
  }
  ?>

  <?php
  require 'navigation/header.php'
  ?>

  <div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
      <div class="container-xl">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
          <div class="col-auto">
            <h1 class="app-page-title mb-0">Input Data</h1>
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
                    <label for="#distrik">Nama Distrik</label>
                  </td>
                  <td>
                    <select class="form-select" name="distrik" id="#distrik">
                      <option value="">Pilih</option>
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
                </tr>

                <tr>
                  <td>
                    <label for="#komoditas">Nama komoditas</label>
                  </td>
                  <td>
                    <select class="form-select" name="komoditas" id="#komoditas">
                      <option value="">Pilih</option>
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
                </tr>

                <tr>
                  <td>
                    <label for="#luasLahan">Luas Lahan (HA)</label>
                  </td>
                  <td>
                    <input id="#luasLahan" type="number" class="form-control" name="luasLahan" step="0.01" min="0" require />
                  </td>
                </tr>

                <tr>
                  <td>
                    <label for="#luasLahan">Luas Tanam Akhir Bulan Lalu</label>
                  </td>
                  <td>
                    <input id="#luasTanamAkhirBulanLalu" type="number" class="form-control" name="luasTanamAkhirBulanLalu" step="0.01" min="0" require />
                  </td>
                </tr>

                <tr>
                  <td>
                    <label><b>Luas Panen (HA)</b></label>
                  </td>
                  <td> </td>
                </tr>

                <tr>
                  <td>
                    <label for="#luasPanenHabisDiBongkar">Habis Dibongkar (HA)</label>
                  </td>
                  <td>
                    <input id="#luasPanenHabisDiBongkar" type="number" class="form-control" name="luasPanenHabisDiBongkar" step="0.01" min="0" require />
                  </td>
                </tr>

                <tr>
                  <td>
                    <label for="#luasPanenBelumHabis">Belum Habis (HA)</label>
                  </td>
                  <td>
                    <input id="#luasPanenBelumHabis" type="number" class="form-control" name="luasPanenBelumHabis" step="0.01" min="0" require />
                  </td>
                </tr>

                <tr>
                  <td>
                    <label for="#luasRusak">Luas Rusak (HA)</label>
                  </td>
                  <td>
                    <input id="#luasRusak" type="number" class="form-control" name="luasRusak" step="0.01" min="0" require />
                  </td>
                </tr>

                <tr>
                  <td>
                    <label for="#luasPenanamanBaru">Luas Penanaman Baru (HA)</label>
                  </td>
                  <td>
                    <input id="#luasPenanamanBaru" type="number" class="form-control" name="luasPenanamanBaru" step="0.01" min="0" require />
                  </td>
                </tr>

                <tr>
                  <td>
                    <label for="#luasTanamAkhirBulanLaporan">Luas Tanam Akhir Bulan Laporan</label>
                  </td>
                  <td>
                    <input id="#luasTanamAkhirBulanLaporan" type="number" class="form-control" name="luasTanamAkhirBulanLaporan" step="0.01" min="0" require />
                  </td>
                </tr>

                <tr>
                  <td>
                    <label><b>Data Produksi (KW)</b></label>
                  </td>
                  <td> </td>
                </tr>

                <tr>
                  <td>
                    <label for="#dataProduksiDiPanenHabis">Dipanen Habis / Dibongkar</label>
                  </td>
                  <td>
                    <input id="#dataProduksiDiPanenHabis" type="number" class="form-control" name="dataProduksiDiPanenHabis" step="0.01" min="0" require />
                  </td>
                </tr>

                <tr>
                  <td>
                    <label for="#dataProduksiBelumHabis">Belum Habis</label>
                  </td>
                  <td>
                    <input id="#dataProduksiBelumHabis" type="number" class="form-control" name="dataProduksiBelumHabis" step="0.01" min="0" require />
                  </td>
                </tr>

                <tr>
                  <td>
                    <label for="#hktppm">Harga Komoditi Tingkat Petani</label>
                  </td>
                  <td>
                    <input id="#hktppm" type="number" class="form-control" name="hktppm" step="0.01" min="0" />
                  </td>
                </tr>

                <tr>
                  <td>
                    <label for="#tanggal">Tanggal</label>
                  </td>
                  <td>
                    <input id="#tanggal" type="date" class="form-control" name="tanggal" require />
                  </td>
                </tr>

                <!-- Tambah Jarak -->
                <tr>
                  <td>
                  </td>
                  <td>
                  </td>
                </tr>

                <tr>
                  <td>
                    <button name="submit" type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">Input Data</button>
                  </td>
                </tr>

              </form>
            </table>
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

  <?php
  require 'navigation/footer.php';
  ?>

  <script>
    //Menampilkan tanggal secara otomatis
    function getCurrentDate() {
      const today = new Date();
      const year = today.getFullYear();
      const month = String(today.getMonth() + 1).padStart(2, '0'); // Bulan dimulai dari 0, jadi tambahkan 1
      const day = String(today.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    }

    // Mengatur nilai input tanggal saat halaman dimuat
    document.addEventListener('DOMContentLoaded', (event) => {
      const tanggalInput = document.getElementById('#tanggal');
      tanggalInput.value = getCurrentDate();
    });
  </script>

  <!-- Javascript -->
  <script src="assets/plugins/popper.min.js"></script>
  <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

  <!-- Page Specific JS -->
  <script src="assets/js/app.js"></script>
</body>

</html>