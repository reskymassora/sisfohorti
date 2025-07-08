<?php
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "u832397905_simfonidb"; 

$servername = "localhost";
$username = "u832397905_R35ky";
$password = "@R35kym4550r4";
$dbname = "u832397905_simfonidb"; 

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// function login
function login($data) {
  global $conn;

  $email = $data['signin-email'];
  $password = $data['signin-password'];

  $query = "SELECT * FROM admin WHERE email = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $row_account = $result->fetch_assoc();

  if ($row_account) {
      // Debug output
      error_log("Email: " . $email);
      error_log("Password: " . $password);
      error_log("Hashed Password: " . $row_account['password']);
      
      if (password_verify($password, $row_account['password'])) {
          return true;
      } else {
          error_log("Password verification failed");
          return false;
      }
  } else {
      error_log("No user found with email: " . $email);
      return false;
  }
}


//Query tampil data
function tampil_data($query)
{
  global $conn;

  $result = mysqli_query($conn, $query);
  $rows = [];

  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }

  return $rows;
}

//Query total luas lahan
function total_luas_lahan($query)
{
  global $conn;
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  return $row['totalLuasLahan'];
}

//Query total luas panen
function total_luas_panen($query)
{
  global $conn;
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  return $row['totalLuasPanen'];
}


//Query total potensi lahan
function total_potensi_lahan($query)
{
  global $conn;
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  return $row['totalPotensiLahan'];
}

// Function hapus data
function delete($id)
{
  global $conn;
  mysqli_query($conn, "DELETE FROM dataTanaman WHERE id = $id");

  return mysqli_affected_rows($conn);
}

// Function hapus data
function deleteKomentar($id)
{
  global $conn;
  mysqli_query($conn, "DELETE FROM komentar WHERE id = $id");

  return mysqli_affected_rows($conn);
}

//Function cari
function cari($keyword){
    
    // Membuat koneksi ke database
    global $conn;
    
    // Memeriksa koneksi
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Mempersiapkan query pencarian
    $keyword = $conn->real_escape_string($keyword);
    $query = "SELECT * FROM dataTanaman WHERE distrik LIKE '%$keyword%' OR komoditas LIKE '%$keyword%'";

    // Melakukan query dan menyimpan hasilnya
    $result = $conn->query($query);
    $data = [];
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    // Menutup koneksi
    $conn->close();
    
    // Mengembalikan hasil pencarian
    return $data;
}

if (isset($_GET['action']) && $_GET['action'] == 'search') {
    if (isset($_GET['q'])) {
        $results = cari($_GET['q']);
        echo json_encode($results);
    }
}


//function get user
function getUserByEmail($email) {
    global $conn;
    // Query untuk mendapatkan informasi pengguna berdasarkan email
    $query = "SELECT * FROM admin WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Periksa apakah pengguna ditemukan
    if ($result->num_rows === 1) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}



// Fungsi untuk menyimpan komentar
function simpanKomentar($nama, $komentar) {
  global $conn;
  $nama = $conn->real_escape_string($nama);
  $komentar = $conn->real_escape_string($komentar);
  $query = "INSERT INTO komentar (nama, komentar, tanggal) VALUES ('$nama', '$komentar', NOW())";
  return $conn->query($query);
}

// Fungsi untuk mengambil komentar terbaru
function ambilKomentar($limit = 5) {
  global $conn;
  $query = "SELECT * FROM komentar ORDER BY tanggal DESC LIMIT $limit";
  $result = $conn->query($query);
  $data = [];
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }
  }
  return $data;
}

