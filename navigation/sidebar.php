<nav id="app-nav-main" class="app-nav app-nav-main flex-grow-1">
  <ul class="app-menu list-unstyled accordion" id="menu-accordion">
    <li class="nav-item">
      <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
      <a class="nav-link <?= $page == 1 ? 'active' : '' ?>" href="dashboard_admin.php">
        <span class="nav-icon text-success">
          <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-house-door" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M7.646 1.146a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 .146.354v7a.5.5 0 0 1-.5.5H9.5a.5.5 0 0 1-.5-.5v-4H7v4a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .146-.354l6-6zM2.5 7.707V14H6v-4a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v4h3.5V7.707L8 2.207l-5.5 5.5z" />
            <path fill-rule="evenodd" d="M13 2.5V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z" />
          </svg>
        </span>
        <span class="nav-link-text text-success">Dashboard</span> </a><!--//nav-link-->
    </li>

    <!--//nav-item-->
    <li class="nav-item">
      <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
      <a class="nav-link <?= $page == 2 ? 'active' : '' ?>" href="input.php">
        <span class="nav-icon text-success">
          <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-card-list" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M14.5 3h-13a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z" />
            <path fill-rule="evenodd" d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5z" />
            <circle cx="3.5" cy="5.5" r=".5" />
            <circle cx="3.5" cy="8" r=".5" />
            <circle cx="3.5" cy="10.5" r=".5" />
          </svg>
        </span>
        <span class="nav-link-text text-success">Input Data</span> </a><!--//nav-link-->
    </li>

    <li class="nav-item">
      <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
      <a class="nav-link <?= $page == 3 ? 'active' : '' ?>" href="grafik_harga.php">
        <span class="nav-icon text-success">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-graph-up" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07" />
          </svg>
        </span>
        <span class="nav-link-text text-success">Grafik Harga</span> </a><!--//nav-link-->
    </li>

    <li class="nav-item">
      <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
      <a class="nav-link <?= $page == 4 ? 'active' : '' ?>" href="komentar.php">
        <span class="nav-icon text-success">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-dots" viewBox="0 0 16 16">
            <path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
            <path d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9 9 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.4 10.4 0 0 1-.524 2.318l-.003.011a11 11 0 0 1-.244.637c-.079.186.074.394.273.362a22 22 0 0 0 .693-.125m.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6-3.004 6-7 6a8 8 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a11 11 0 0 0 .398-2" />
          </svg>
        </span>
        <span class="nav-link-text text-success">Komentar</span> </a><!--//nav-link-->
    </li>


    <!--//nav-item-->
  </ul>
  <!--//app-menu-->
</nav>