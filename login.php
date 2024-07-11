<!DOCTYPE html>
<html lang="en">

<head>
	<title>Login</title>

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

<body class="app app-login p-0">
	<!-- logic code -->
	<?php
	session_start();
	require 'function.php';

	if (isset($_POST['login'])) {
		$email = $_POST['signin-email'];
		if (login($_POST) === true) {
			$_SESSION['email'] = $email;
			echo "<script>
            Swal.fire({
                title: 'Sukses!',
                text: 'Login berhasil',
                icon: 'success',
                confirmButtonColor: '#069118',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'dashboard_admin.php';
                }
            });
        </script>";
		} else {
			echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Gagal login',
                icon: 'error',
                confirmButtonText: 'Coba lagi'
            });
        </script>";
		}
	}
	?>


	<div class="row g-0 app-auth-wrapper">
		<div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
			<div class="d-flex flex-column align-content-end">
				<div class="app-auth-body mx-auto">
					<div class="app-auth-branding mb-4"><a class="app-logo" href="#"><img class="logo-icon me-2" src="assets/images/logo-utama.jpeg" alt="logo"></a></div>
					<h2 class="auth-heading text-center mb-3 text-success">Simfoni Hortikultura</h2>
					<h2 class="auth-heading text-center mb-5">Dinas Tanaman Pangan Hortikultura Dan Perkebunan <br> Kabupaten Mimika</h2>
					<div class="auth-form-container text-start">
						<form class="auth-form login-form" method="post">
							<div class="email mb-3">
								<label class="sr-only" for="signin-email">Email</label>
								<input id="signin-email" name="signin-email" type="email" class="form-control signin-email" placeholder="Email address" required="required">
							</div><!--//form-group-->
							<div class="password mb-3">
								<label class="sr-only" for="signin-password">Password</label>
								<input id="signin-password" name="signin-password" type="password" class="form-control signin-password" placeholder="Password" required="required">
								<div class="extra mt-3 row justify-content-between">
									<div class="col-6">
										<div class="form-check">
											<input class="form-check-input" type="checkbox" value="" id="showPassword">
											<label class="form-check-label" for="showPassword">
												Show Password
											</label>
										</div>
									</div><!--//col-6-->
									<div class="col-6">
										<div class="forgot-password text-end">
											<!-- <a href="reset-password.html">Forgot password?</a> -->
										</div>
									</div><!--//col-6-->
								</div><!--//extra-->
							</div><!--//form-group-->
							<div class="text-center">
								<button type="submit" name="login" class="btn app-btn-primary w-100 theme-btn mx-auto">Log In</button>
							</div>
						</form>
					</div><!--//auth-form-container-->

				</div><!--//auth-body-->
			</div><!--//flex-column-->
		</div><!--//auth-main-col-->
		<div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
			<div class="auth-background-holder">
			</div>
			<div class="auth-background-mask"></div>
			<div class="auth-background-overlay p-3 p-lg-5">
				<div class="d-flex flex-column align-content-end h-100">
					<div class="h-100"></div>
				</div>
			</div>
		</div>

	</div><!--//row-->

	<script>
		const passwordInput = document.getElementById('signin-password');
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
	</script>

</body>

</html>