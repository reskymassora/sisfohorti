<!DOCTYPE html>
<html lang="en">

<head>
	<title>Register</title>

	<!-- Meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<meta name="description" content="Portal - Bootstrap 5 Admin Dashboard Template For Developers">
	<meta name="author" content="Xiaoying Riley at 3rd Wave Media">
	<link rel="shortcut icon" href="favicon.ico">

	<!-- SweetAlert2 JS -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<!-- FontAwesome JS-->
	<script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

	<!-- App CSS -->
	<link id="theme-style" rel="stylesheet" href="assets/css/portal.css">


	<link rel="shortcut icon" href="assets/images/logo-utama.jpeg">

</head>

<body class="app app-signup p-0">

	<!-- Logic Code -->
	<?php
require 'function.php';

if (isset($_POST['submit'])) {
    // ambil data dari form
    $userFullName = $_POST['fullname'];
    $userEmail = $_POST['email'];
    $userPassword = $_POST['password'];

    // Hash password menggunakan password_hash()
    $hashedPassword = password_hash($userPassword, PASSWORD_DEFAULT);

    // Proses upload gambar
    $fotoProfil = '';
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

        if(move_uploaded_file($fileTmpPath, $dest_path)) {
            $fotoProfil = $newFileName;
        } else {
            echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Gagal mengupload gambar.',
                icon: 'error',
                confirmButtonText: 'Coba lagi'
            });
            </script>";
            exit;
        }
    }

    // Query untuk memasukkan data ke dalam tabel admin dengan password yang sudah di-hash dan nama gambar
    $query = "INSERT INTO admin (fullname, email, password, fotoProfil)
              VALUES ('$userFullName', '$userEmail', '$hashedPassword', '$fotoProfil')";

    $execute = mysqli_query($conn, $query);

    if ($execute) {
        // Jika data berhasil dimasukkan, tampilkan popup dan redirect ke halaman login
        echo "<script>
        Swal.fire({
                title: 'Sukses!',
                text: 'Pendaftaran Berhasil',
                icon: 'success',
                confirmButtonColor: '#069118',
        }).then((result) => {
                if (result.isConfirmed) {
                        window.location = 'login.php';
                }
        });
        </script>";
        exit; // Hentikan eksekusi script agar tidak melanjutkan ke bagian bawah
    } else {
        echo "
        <script>
            Swal.fire({
                title: 'Error!',
                text: 'Gagal login',
                icon: 'error',
                confirmButtonText: 'Coba lagi'
            });
        </script>";
    }

    // Tutup koneksi ke database jika sudah selesai
    mysqli_close($conn);
}
?>

	<!-- End of logic code -->


	<div class="row g-0 app-auth-wrapper">
		<div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
			<div class="d-flex flex-column align-content-end">
				<div class="app-auth-body mx-auto">
					<div class="app-auth-branding mb-4"><a class="app-logo" href="#"><img class="logo-icon me-2" src="assets/images/logo-utama.jpeg" alt="logo"></a></div>
					<h2 class="auth-heading text-center mb-4">Register</h2>
					<div class="auth-form-container text-start mx-auto">
						
						<form class="auth-form auth-signup-form" method="post" enctype="multipart/form-data">
							<div class="email mb-3">
								<label class="sr-only" for="signup-name">Your Name</label>
								<input id="signup-name" name="fullname" type="text" class="form-control signup-name" placeholder="Full name" required="required">
							</div>
							<div class="email mb-3">
								<label class="sr-only" for="signup-email">Your Email</label>
								<input id="signup-email" name="email" type="email" class="form-control signup-email" placeholder="Email" required="required">
							</div>
							<div class="password mb-3">
								<label class="sr-only" for="signup-password">Password</label>
								<input id="signup-password" name="password" type="password" class="form-control signup-password" placeholder="Create a password" required="required">
								<div class="col-6 mt-2">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="" id="showPassword">
										<label class="form-check-label" for="showPassword">
											Show Password
										</label>
									</div>
								</div>
							</div>
							<div class="col text-center mt-3">
								<div class="row">
									<a class="app-logo">
										<img id="profilePhoto" class="rounded-circle logo-icon me-2" src="assets/images/codicon--account.png" alt="logo">
										<p>Foto Profil</p>
									</a>
								</div>
								<div class="row">
									<div class="mb-2 mt-2">
										<input class="form-control" type="file" id="gambar" name="gambar" accept="image/*" onchange="previewImage(event)">
									</div>
								</div>
							</div>
							<div class="text-center">
								<button type="submit" name="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">Sign Up</button>
							</div>
						</form>
						
						<div class="auth-option text-center pt-3">Already have an account? <a class="text-link" href="login.php">Log in</a></div>
					</div>
				</div>
			</div><!--//flex-column-->
		</div><!--//auth-main-col-->
		<div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
			<div class="auth-background-holder">
			</div>
			<div class="auth-background-mask"></div>
		</div><!--//auth-background-overlay-->
	</div><!--//auth-background-col-->

	</div><!--//row-->

	<script>

		//Hide Password
		const passwordInput = document.getElementById('signup-password');
		const showPasswordBtn = document.getElementById('showPassword');

		showPasswordBtn.addEventListener('click', togglePasswordVisibility);

		function togglePasswordVisibility() {
			if (passwordInput.type === 'password') {
				passwordInput.type = 'text';
				showPasswordBtn.textContent = 'Hide Password';
			} else {
				passwordInput.type = 'password';
				showPasswordBtn.textContent = 'Show Password';
			}
		}

		//Preview profil
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