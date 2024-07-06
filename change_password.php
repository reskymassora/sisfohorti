<!DOCTYPE html>
<html lang="en">

<head>
  <title>Account Info</title>

  <!-- Meta -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta name="description" content="Portal - Bootstrap 5 Admin Dashboard Template For Developers">
  <meta name="author" content="Xiaoying Riley at 3rd Wave Media">

  <link rel="shortcut icon" href="assets/images/logo-utama.jpeg">

  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- FontAwesome JS-->
  <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

  <!-- App CSS -->
  <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">

</head>

<body class="app app-reset-password p-0">
  <?php
  session_start();

  require_once 'function.php'; // Menggunakan require_once

  // Fungsi untuk melindungi dari serangan session hijacking
  function regenerateSession()
  {
    if (!isset($_SESSION['last_regenerate'])) {
      $_SESSION['last_regenerate'] = time();
    } else if (time() - $_SESSION['last_regenerate'] > 300) { // 300 detik atau 5 menit
      session_regenerate_id(true);
      $_SESSION['last_regenerate'] = time();
    }
  }

  // Fungsi untuk mengharuskan login ulang setelah periode tidak aktif
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

  // Cek apakah pengguna sudah login
  if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
  }

  enforceSessionTimeout();
  regenerateSession();

  $email = $_SESSION['email'];
  $userInfo = getUserByEmail($email);

  if ($userInfo !== false) {
    // Ambil data pengguna dari database
    $email = htmlspecialchars($userInfo['email'], ENT_QUOTES, 'UTF-8');
    $hashedPassword = $userInfo['password']; // Hash password dari database
    $profilePhoto = htmlspecialchars($userInfo['fotoProfil'], ENT_QUOTES, 'UTF-8');

    // Ambil password lama dan baru yang diinput oleh pengguna dari form
    if (isset($_POST['change'])) {
      $oldPassword = $_POST['old_password'];
      $newPassword = $_POST['new_password'];
      $confirmNewPassword = $_POST['confirm_new_password'];

      // Verifikasi password lama dan kesesuaian password baru dengan konfirmasi password
      if (password_verify($oldPassword, $hashedPassword) && $newPassword === $confirmNewPassword) {
        // Hash password baru
        $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password ke database menggunakan prepared statement
        $sql = "UPDATE admin SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $newHashedPassword, $email);

        if ($stmt->execute()) {
          if ($stmt->affected_rows > 0) {
            echo "<script>
                Swal.fire({
                    title: 'Sukses!',
                    text: 'Password berhasil diubah.',
                    icon: 'success',
                    confirmButtonColor: '#069118'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = 'dashboard_admin.php';
                    }
                });
                </script>";
          } else {
            echo "<script>
                Swal.fire({
                    title: 'Sukses!',
                    text: 'Tidak ada perubahan password.',
                    icon: 'info',
                    confirmButtonColor: '#069118'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = 'change_password.php';
                    }
                });
                </script>";
          }
        } else {
          echo "<script>
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat mengubah password.',
                icon: 'error',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'change_password.php';
                }
            });
            </script>";
        }

        $stmt->close();
      } else {
        echo "<script>
        Swal.fire({
            title: 'Gagal!',
            text: 'Password lama tidak valid atau password baru tidak cocok dengan konfirmasi password.',
            icon: 'error',
            confirmButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'change_password.php';
            }
        });
        </script>";
      }
    }
  } else {
    echo "<script>
  Swal.fire({
      title: 'Gagal!',
      text: 'Informasi pengguna tidak ditemukan.',
      icon: 'error',
      confirmButtonColor: '#d33'
  }).then((result) => {
      if (result.isConfirmed) {
          window.location = 'change_password.php';
      }
  });
  </script>";
    exit();
  }


  // Tutup koneksi ke database jika sudah selesai
  mysqli_close($conn);
  ?>

  <div class="row g-0 app-auth-wrapper">
    <div class="col-12 col-md-12 col-lg-12 auth-main-col text-center p-5">
      <div class="d-flex flex-column align-content-end">
        <div class="app-auth-body mx-auto">
          <div class="app-auth-branding mb-4">
            <a class="app-logo">
              <img id="profilePhoto" class="rounded-circle logo-icon me-2" src="assets/images/profiles/<?= $profilePhoto ?>" alt="logo">
            </a>
          </div>
          <h2 class="auth-heading text-center mb-4">Account Info</h2>

          <div class="auth-form-container text-left">

            <form class="auth-form resetpass-form" method="post" enctype="multipart/form-data">
              <div class="email mb-3">
                <input id="reg-fullname" name="old_password" type="text" class="form-control" placeholder="Password Lama" required="required" value="">
              </div>

              <div class="email mb-3">
                <input id="reg-email" name="new_password" type="text" class="form-control" placeholder="Password Baru" required="required" value="">
              </div>

              <div class="email mb-3">
                <input id="reg-email" name="confirm_new_password" type="text" class="form-control login-email" placeholder="Konfirmasi Password" required="required" value="">
              </div>
              <div class="text-center mt-3">
                <button type="submit" name="change" class="btn app-btn-primary btn-block theme-btn mx-auto">Simpan</button>
              </div>
            </form>
          </div><!--//auth-form-container-->
        </div><!--//auth-body-->

        <footer class="app-auth-footer">
          <div class="container text-center py-3">
            <!--/* This template is free as long as you keep the footer attribution link. If you'd like to use the template without the attribution link, you can buy the commercial license via our website: themes.3rdwavemedia.com Thank you for your support. :) */-->
            <!-- <small class="copyright">Designed with <span class="sr-only">love</span><i class="fas fa-heart" style="color: #fb866a;"></i> by <a class="app-link" href="http://themes.3rdwavemedia.com" target="_blank">Xiaoying Riley</a> for developers</small> -->

          </div>
        </footer><!--//app-auth-footer-->
      </div><!--//flex-column-->
    </div><!--//auth-main-col-->
  </div><!--//row-->

</body>

</html>