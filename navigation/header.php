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

          <!-- Time -->
          <span class="col-auto">
            <span id="day">Jumat</span>
            <span id="time">12:11</span>
          </span>

          <div class="app-utilities col-auto me-4">
            <div class="app-utility-item app-user-dropdown dropdown">
              <span class="me-2"> <?= $fullname ?></span>
              <a class="dropdown-toggle" id="user-dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                <img class="rounded-circle" src="assets/images/profiles/<?= $profilePhoto ?>" alt="user profile" />
              </a>
              <ul class="dropdown-menu" aria-labelledby="user-dropdown-toggle">
                <li>
                  <a class="dropdown-item" href="account.php">Account</a>
                  <!-- ?email=<?= urlencode($email) ?> -->
                </li>
                <li>
                  <hr class="dropdown-divider" />
                </li>
                <li>
                  <a id="logoutButton" class="dropdown-item" href="logout.php">Log Out</a>
                </li>
              </ul>
            </div>
            <!--//app-user-dropdown-->
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
        <a class="app-logo" href="#">
          <img class="logo-icon me-2 border border-success rounded-circle" src="assets/images/logo-utama-nobg.png" alt="logo" />
          <span class="logo-text">Admin Page</span></a>
      </div>

      <?php
      require 'navigation/sidebar.php'
      ?>

    </div>
    <!--//sidepanel-inner-->
  </div>
  <!--//app-sidepanel-->
</header>

<script>
  //Update clock
  function updateClock() {
    const now = new Date();

    const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
    const day = days[now.getDay()];

    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const seconds = now.getSeconds().toString().padStart(2, '0');

    document.getElementById('day').textContent = day;
    document.getElementById('time').textContent = `${hours}:${minutes}:${seconds}`;
  }

  setInterval(updateClock, 1000);
  updateClock(); // panggil sekali saat halaman dimuat untuk menampilkan waktu segera

  
  //Konfirmasi Logout
  document.getElementById('logoutButton').addEventListener('click', function(event) {
      event.preventDefault(); // Cegah aksi default tautan

      Swal.fire({
        title: 'Yakin ingin keluar?',
        text: "Anda akan keluar dari sesi ini!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, keluar!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          // Arahkan ke URL logout jika pengguna mengonfirmasi
          window.location.href = 'logout.php';
        }
      });
    });
</script>
<!--//app-header-->