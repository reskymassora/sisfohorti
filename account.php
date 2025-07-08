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
function regenerateSession() {
    if (!isset($_SESSION['last_regenerate'])) {
        $_SESSION['last_regenerate'] = time();
    } else if (time() - $_SESSION['last_regenerate'] > 300) { // 300 detik atau 5 menit
        session_regenerate_id(true);
        $_SESSION['last_regenerate'] = time();
    }
}

// Fungsi untuk mengharuskan login ulang setelah periode tidak aktif
function enforceSessionTimeout() {
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
    $fullname = htmlspecialchars($userInfo['fullname'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($userInfo['email'], ENT_QUOTES, 'UTF-8');
    $profilePhoto = htmlspecialchars($userInfo['fotoProfil'], ENT_QUOTES, 'UTF-8');
} else {
    echo "<p>Informasi pengguna tidak ditemukan.</p>";
    exit();
}

// Proses pembaruan profil
if (isset($_POST['submit'])) {
    $errors = [];

    if (isset($_POST['reg-fullname'])) {
        $newFullName = $_POST['reg-fullname'];
    } else {
        $errors[] = "Fullname is missing.";
    }

    if (isset($_POST['reg-email'])) {
        $newEmail = $_POST['reg-email'];
    } else {
        $errors[] = "Email is missing.";
    }

    if (count($errors) === 0) {
        // Proses upload gambar jika ada
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['gambar']['tmp_name'];
            $fileName = $_FILES['gambar']['name'];
            $fileSize = $_FILES['gambar']['size'];
            $fileType = $_FILES['gambar']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Generate nama baru untuk gambar
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

            // Tentukan path dimana gambar akan disimpan
            $uploadFileDir = 'assets/images/profiles/';
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Hapus gambar lama jika ada
                if (!empty($profilePhoto) && file_exists($uploadFileDir . $profilePhoto)) {
                    unlink($uploadFileDir . $profilePhoto);
                }
                $profilePhoto = $newFileName;
            } else {
                $errors[] = "Gagal mengupload gambar.";
            }
        }

        if (count($errors) === 0) {
            // Query untuk memperbarui data pengguna
            $query = "UPDATE admin SET fullname='$newFullName', email='$newEmail', fotoProfil='$profilePhoto' WHERE email='$email'";

            $execute = mysqli_query($conn, $query);

            if ($execute) {
                // Jika data berhasil diperbarui, tampilkan popup
                echo "<script>
                Swal.fire({
                        title: 'Sukses!',
                        text: 'Profil berhasil diperbarui.',
                        icon: 'success',
                        confirmButtonColor: '#069118',
                }).then((result) => {
                        if (result.isConfirmed) {
                          window.location.href = 'dashboard_admin.php';
                        }
                });
                </script>";
                exit; // Hentikan eksekusi script agar tidak melanjutkan ke bagian bawah
            } else {
                $errors[] = "Gagal memperbarui profil.";
            }
        }
    }

    if (count($errors) > 0) {
        echo "<script>
        Swal.fire({
            title: 'Error!',
            html: '" . implode("<br>", $errors) . "',
            icon: 'error',
            confirmButtonText: 'Coba lagi'
        });
        </script>";
    }
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
								<input id="reg-fullname" name="reg-fullname" type="text" class="form-control login-email" placeholder="Your Name" required="required" value="<?= $fullname; ?>">
							</div>

							<div class="email mb-3">
								<input id="reg-email" name="reg-email" type="email" class="form-control login-email" placeholder="Your Email" required="required" value="<?= $email; ?>">
							</div>

							<div class="mb-2 mt-2">
								<input class="form-control" type="file" id="gambar" name="gambar" accept="image/*" onchange="previewImage(event)">
							</div>

							<div class="text-center mt-3">
								<button type="submit" name="submit" class="btn app-btn-primary btn-block theme-btn mx-auto">Save</button>
							</div>

							<div class="text-center mt-3">
								<button type="button" name="submit" class="btn app-btn-secondary btn-block theme-btn mx-auto" onclick="window.location.href='change_password.php';">Change Password</button>
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


	<!-- Script -->
	<script>
		function previewImage(event) {
			const input = event.target;
			if (input.files && input.files[0]) {
				const file = input.files[0];
				if (file.type.startsWith('image/')) {
					const reader = new FileReader();
					reader.onload = function(e) {
						const profilePhoto = document.getElementById('profilePhoto');
						profilePhoto.src = e.target.result;
					};
					reader.readAsDataURL(file);
				} else {
					alert('Please select a valid image file.');
				}
			}
		}
	</script>

</body>

</html>