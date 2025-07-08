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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Sweatalert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <title>Document</title>
</head>

<body>

  <!-- logic code -->
  <?php
  require 'function.php';
  $id = $_GET["id"];

  if (deleteKomentar($id) > 0) {
    echo "
      <script>
          Swal.fire({
              title: 'Berhasil!',
              text: 'Data berhasil dihapus',
              icon: 'success',
              confirmButtonColor: '#4CAF50',
          }).then((result) => {
              if (result.isConfirmed) {
                  window.location = 'komentar.php';
              }
          });
      </script>";
  } else {
    echo "
      <script>
          Swal.fire({
              title: 'Gagal!',
              text: 'Data gagal dihapus',
              icon: 'error',
              confirmButtonColor: '#d42e1c',
          }).then((result) => {
              if (result.isConfirmed) {
                  window.location = 'komentar.php';
              }
          });
      </script>";
  }
  ?>
  <!-- End of logic code -->

</body>

</html>