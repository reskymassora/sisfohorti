<?php
session_start();

if ($_SESSION != TRUE) {
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

  <meta name="description" content="Simfoni Hortikultura" />
  <meta name="author" content="Xiaoying Riley at 3rd Wave Media" />
  <link rel="shortcut icon" href="favicon.ico" />

  <!-- FontAwesome JS-->
  <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>


  <!-- Sweatalert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- App CSS -->
  <link id="theme-style" rel="stylesheet" href="assets/css/portal.css" />
</head>

<body class="app">

  <!-- Logic Code -->
  <?php
  require 'function.php';

  $id = $_GET["id"];

  // query data peserta berdasarkan id
  $data = tampil_data("SELECT * FROM dataTanaman WHERE id = '$id'")[0];

  $distrik = isset($data['distrik']) ? htmlspecialchars($data['distrik'], ENT_QUOTES, 'UTF-8') : '';
  $komoditas = isset($data['komoditas']) ? htmlspecialchars($data['komoditas'], ENT_QUOTES, 'UTF-8') : '';

  // edit data
  if (isset($_POST['submit'])) {
    // Cek inputan kosong atau tidak
    if (empty($_POST['distrik']) || empty($_POST['komoditas']) || empty($_POST['luasLahan']) || empty($_POST['luasPanen']) || empty($_POST['dp']) || empty($_POST['tanggal'])) {
      echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Data tidak boleh kosong',
            icon: 'error',
            confirmButtonColor: '#d42e1c',
        });
        </script>";
    } else {
      // Ambil data dari form
      $distrik = strtoupper($_POST['distrik']);
      $komoditas = strtoupper($_POST['komoditas']);
      $luasLahan = $_POST['luasLahan'];
      $luasPanen = $_POST['luasPanen'];
      $dp = $_POST['dp'];
      $hktppm = $_POST['hktppm'];
      $tanggal = $_POST['tanggal'];

      // Cek apakah ada perubahan data
      if (
        $distrik == $data['distrik'] &&
        $komoditas == $data['komoditas'] &&
        $luasLahan == $data['luasLahan'] &&
        $luasPanen == $data['luasPanen'] &&
        $dp == $data['dataProduksi'] &&
        $hktppm == $data['hktppm'] &&
        $tanggal == $data['tanggal']
      ) {
        echo "<script>
            Swal.fire({
                title: 'Tidak ada perubahan',
                text: 'Tidak ada data yang diubah',
                icon: 'info',
                confirmButtonColor: '#3085d6',
            });
            </script>";
      } else {
        // Query untuk mengupdate data
        $query = "UPDATE dataTanaman SET
                distrik = '$distrik',
                komoditas = '$komoditas',
                luasLahan = '$luasLahan',
                luasPanen = '$luasPanen',
                dataProduksi = '$dp',
                hktppm = '$hktppm',
                tanggal = '$tanggal'
                WHERE id = '$id'";

        // jalankan query
        $result = mysqli_query($conn, $query);

        // cek hasil query
        if (mysqli_affected_rows($conn) > 0) {
          echo "<script>
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Data berhasil diubah',
                        icon: 'success',
                        confirmButtonColor: '#4CAF50',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = 'dashboard_admin.php';
                        }
                    });
                    </script>";
        } else {
          echo "<script>
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Data gagal diubah',
                        icon: 'error',
                        confirmButtonColor: '#d42e1c',
                    });
                    </script>";
        }
      }
    }
  }
  ?>

  <!-- End of logic code -->

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


            <!--//app-utilities-->
          </div>
          <!--//row-->
        </div>
        <!--//app-header-content-->
      </div>
      <!--//container-fluid-->
    </div>
    <!--//app-header-inner-->
    <div id="app-sidepanel" class="app-sidepanel sidepanel-hidden">
      <div id="sidepanel-drop" class="sidepanel-drop"></div>
      <div class="sidepanel-inner d-flex flex-column">
        <a href="#" id="sidepanel-close" class="sidepanel-close d-xl-none">&times;</a>
        <div class="app-branding">
          <a class="app-logo" href="index.html"><img class="logo-icon me-2" src="assets/images/app-logo.svg" alt="logo" /><span class="logo-text">Admin Page</span></a>
        </div>

        <?php
        require 'navigation/sidebar.php'
        ?>

      </div>
      <!--//sidepanel-inner-->
    </div>
    <!--//app-sidepanel-->
  </header>
  <!--//app-header-->

  <div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
      <div class="container-xl">
        <div class="row g-3 mb-4 align-items-center justify-content-between">
          <div class="col-auto">
            <h1 class="app-page-title mb-0">Edit Data</h1>
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
                    <select class="form-select" name="distrik" id="distrik">
                      <option value="">Pilih</option>
                      <option value="MIMIKA BARAT" <?= $distrik == 'MIMIKA BARAT' ? 'selected' : '' ?>>MIMIKA BARAT</option>
                      <option value="MIMIKA BARAT TENGAH" <?= $distrik == 'MIMIKA BARAT TENGAH' ? 'selected' : '' ?>>MIMIKA BARAT TENGAH</option>
                      <option value="MIMIKA BARAT JAUH" <?= $distrik == 'MIMIKA BARAT JAUH' ? 'selected' : '' ?>>MIMIKA BARAT JAUH</option>
                      <option value="MIMIKA TIMUR" <?= $distrik == 'MIMIKA TIMUR' ? 'selected' : '' ?>>MIMIKA TIMUR</option>
                      <option value="MIMIKA TENGAH" <?= $distrik == 'MIMIKA TENGAH' ? 'selected' : '' ?>>MIMIKA TENGAH</option>
                      <option value="MIMIKA TIMUR JAUH" <?= $distrik == 'MIMIKA TIMUR JAUH' ? 'selected' : '' ?>>MIMIKA TIMUR JAUH</option>
                      <option value="MIMIKA BARU" <?= $distrik == 'MIMIKA BARU' ? 'selected' : '' ?>>MIMIKA BARU</option>
                      <option value="KUALA KENCANA" <?= $distrik == 'KUALA KENCANA' ? 'selected' : '' ?>>KUALA KENCANA</option>
                      <option value="TEMBAGAPURA" <?= $distrik == 'TEMBAGAPURA' ? 'selected' : '' ?>>TEMBAGAPURA</option>
                      <option value="AGIMUGA" <?= $distrik == 'AGIMUGA' ? 'selected' : '' ?>>AGIMUGA</option>
                      <option value="JITA" <?= $distrik == 'JITA' ? 'selected' : '' ?>>JITA</option>
                      <option value="JILA" <?= $distrik == 'JILA' ? 'selected' : '' ?>>JILA</option>
                      <option value="IWAKA" <?= $distrik == 'IWAKA' ? 'selected' : '' ?>>IWAKA</option>
                      <option value="KWAMKI NARAMA" <?= $distrik == 'KWAMKI NARAMA' ? 'selected' : '' ?>>KWAMKI NARAMA</option>
                      <option value="WANIA" <?= $distrik == 'WANIA' ? 'selected' : '' ?>>WANIA</option>
                      <option value="AMAR" <?= $distrik == 'AMAR' ? 'selected' : '' ?>>AMAR</option>
                      <option value="ALAMA" <?= $distrik == 'ALAMA' ? 'selected' : '' ?>>ALAMA</option>
                      <option value="HOYA" <?= $distrik == 'HOYA' ? 'selected' : '' ?>>HOYA</option>
                    </select>
                  </td>
                </tr>

                <tr>
                  <td>
                    <label for="#komoditas">Nama komoditas</label>
                  </td>
                  <td>
                    <select class="form-select" name="komoditas" id="komoditas">
                      <option value="">Pilih</option>
                      <option value="BUNCIS" <?= $komoditas == 'BUNCIS' ? 'selected' : '' ?>>BUNCIS</option>
                      <option value="KETIMUN" <?= $komoditas == 'KETIMUN' ? 'selected' : '' ?>>KETIMUN</option>
                      <option value="LABU SIAM" <?= $komoditas == 'LABU SIAM' ? 'selected' : '' ?>>LABU SIAM</option>
                      <option value="KANGKUNG" <?= $komoditas == 'KANGKUNG' ? 'selected' : '' ?>>KANGKUNG</option>
                      <option value="BAYAM" <?= $komoditas == 'BAYAM' ? 'selected' : '' ?>>BAYAM</option>
                      <option value="MELON" <?= $komoditas == 'MELON' ? 'selected' : '' ?>>MELON</option>
                      <option value="SEMANGKA" <?= $komoditas == 'SEMANGKA' ? 'selected' : '' ?>>SEMANGKA</option>
                      <option value="BAWANG DAUN" <?= $komoditas == 'BAWANG DAUN' ? 'selected' : '' ?>>BAWANG DAUN</option>
                      <option value="KUBIS" <?= $komoditas == 'KUBIS' ? 'selected' : '' ?>>KUBIS</option>
                      <option value="PETSAI/SAWI" <?= $komoditas == 'PETSAI/SAWI' ? 'selected' : '' ?>>PETSAI/SAWI</option>
                      <option value="KACANG PANJANG" <?= $komoditas == 'KACANG PANJANG' ? 'selected' : '' ?>>KACANG PANJANG</option>
                      <option value="CABAI BESAR" <?= $komoditas == 'CABAI BESAR' ? 'selected' : '' ?>>CABAI BESAR</option>
                      <option value="CABAI RAWIT" <?= $komoditas == 'CABAI RAWIT' ? 'selected' : '' ?>>CABAI RAWIT</option>
                      <option value="TOMAT" <?= $komoditas == 'TOMAT' ? 'selected' : '' ?>>TOMAT</option>
                      <option value="TERONG" <?= $komoditas == 'TERONG' ? 'selected' : '' ?>>TERONG</option>
                      <option value="MANGGA" <?= $komoditas == 'MANGGA' ? 'selected' : '' ?>>MANGGA</option>
                      <option value="ALPUKAT" <?= $komoditas == 'ALPUKAT' ? 'selected' : '' ?>>ALPUKAT</option>
                      <option value="NANAS" <?= $komoditas == 'NANAS' ? 'selected' : '' ?>>NANAS</option>
                      <option value="PEPAYA" <?= $komoditas == 'PEPAYA' ? 'selected' : '' ?>>PEPAYA</option>
                      <option value="PISANG" <?= $komoditas == 'PISANG' ? 'selected' : '' ?>>PISANG</option>
                      <option value="SALAK" <?= $komoditas == 'SALAK' ? 'selected' : '' ?>>SALAK</option>
                      <option value="JERUK MANIS" <?= $komoditas == 'JERUK MANIS' ? 'selected' : '' ?>>JERUK MANIS</option>
                      <option value="JERUK NIPIS" <?= $komoditas == 'JERUK NIPIS' ? 'selected' : '' ?>>JERUK NIPIS</option>
                      <option value="RAMBUTAN" <?= $komoditas == 'RAMBUTAN' ? 'selected' : '' ?>>RAMBUTAN</option>
                      <option value="DURIAN" <?= $komoditas == 'DURIAN' ? 'selected' : '' ?>>DURIAN</option>
                      <option value="NANGKA" <?= $komoditas == 'NANGKA' ? 'selected' : '' ?>>NANGKA</option>
                      <option value="JAMBU BIJI" <?= $komoditas == 'JAMBU BIJI' ? 'selected' : '' ?>>JAMBU BIJI</option>
                      <option value="JAMBU AIR" <?= $komoditas == 'JAMBU AIR' ? 'selected' : '' ?>>JAMBU AIR</option>
                    </select>
                  </td>
                </tr>

                <tr>
                  <td>
                    <label for="#luasLahan">Luas Tanam (HA)</label>
                  </td>
                  <td>
                    <input id="#luasLahan" type="number" class="form-control" name="luasLahan" value="<?= $data["luasLahan"] ?>" step="0.01" min="0" require />
                  </td>
                </tr>

                <tr>
                  <td>
                    <label for="#luasPanen">Luas Panen (HA)</label>
                  </td>
                  <td>
                    <input id="#luasPanen" type="number" class="form-control" name="luasPanen" value="<?= $data["luasPanen"] ?>" step="0.01" min="0" require />
                  </td>
                </tr>

                <tr>
                  <td>
                    <label for="#dp">Data Produksi (KW)</label>
                  </td>
                  <td>
                    <input id="#dp" type="number" class="form-control" name="dp" value="<?= $data["dataProduksi"] ?>" step="0.01" min="0" require />
                  </td>
                </tr>

                <tr>
                  <td>
                    <label for="#hktppm">Harga Komoditi Tingkat Petani</label>
                  </td>
                  <td>
                    <input id="#hktppm" type="number" class="form-control" name="hktppm" value="<?= $data["hktppm"] ?>" step="0.01" min="0"/>
                  </td>
                </tr>

                <tr>
                  <td>
                    <label for="#tanggal">Tanggal</label>
                  </td>
                  <td>
                    <input id="#tanggal" type="date" class="form-control" name="tanggal" value="<?= $data["tanggal"] ?>" require />
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
                    <button name="submit" type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">Edit Data</button>
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
  <!--//app-content-->


  <!--//app-footer-->
  </div>
  <!--//app-wrapper-->

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