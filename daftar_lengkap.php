<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

  <!-- App CSS -->
  <link id="theme-style" rel="stylesheet" href="assets/css/portal.css" />

  <link rel="shortcut icon" href="assets/images/logo-utama.jpeg">

  <style>
    #main-table {
      display: visible;
      /* Secara default, tampilkan tabel utama */
    }
    #main-table.hidden {
      display: none;
    }
  </style>
  
</head>

<body class="container">

  <?php

  require 'function.php';

  $page = 1;



  $daftarTanaman = tampil_data("SELECT * FROM dataTanaman");

  $totalLuasTanam = total_luas_lahan("SELECT SUM(luasLahan) as totalLuasLahan FROM dataTanaman");

  $totalLuasPanen = total_luas_panen("SELECT SUM(luasPanen) as totalLuasPanen FROM dataTanaman");

  ?>

  <div >
    <h2 class="mb-3 text-center mt-4" id="tabel_komoditas">INFORMASI KOMODITAS</h2>
  </div>

  <div class="app-search-box col mb-3 mt-4">
    <form class="app-search-form" method="post">
      <input type="text" id="search" placeholder="Search... [ Nama distrik, Nama komoditas ]" name="keyword" class="form-control search-input" />
    </form>

    <!--//row-->
    <div class="tab-content mt-3" id="orders-table-tab-content">
      <div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">
        <div class="app-card app-card-orders-table shadow-sm mb-5">
          <div class="app-card-body">
            <div id="results" class="tab-content table-responsive" id="orders-table-tab-content">
              <!-- Tabel hasil pencarian akan dimasukkan di sini oleh JavaScript -->
            </div>
            <div class="table-responsive">
              <table class="table app-table-hover mb-0 text-left" id="main-table">
                <thead>
                  <tr>
                    <th class="cell">No.</th>
                    <th class="cell">Distrik</th>
                    <th class="cell">Komoditas</th>
                    <th class="cell">Luas Tanam <br> (HA)</th>
                    <th class="cell">Luas Panen <br> (HA)</th>
                    <th class="cell">Data Produksi <br> (KW)</th>
                    <th class="cell">Harga Komoditi Tingkat <br> Petani (Minggu)</th>
                  </tr>
                </thead>

                <tbody>
                  <?php $i = 1; ?>
                  <?php foreach ($daftarTanaman as $data) : ?>
                    <tr>
                      <td class="cell"><?= $i; ?></td>
                      <td class="cell"><?= $data['distrik'] ?></td>
                      <td class="cell"><?= $data['komoditas'] ?></td>
                      <td class="cell"><?= $data['luasLahan'] ?></td>
                      <td class="cell"><?= $data['luasPanen'] ?></td>
                      <td class="cell"><?= $data['dataProduksi'] ?></td>
                      <td class="cell">Rp <?= $data['hktppm'] ?></td>

                    </tr>
                    <?php $i++; ?>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Fungsi untuk menangani pencarian
    function handleSearch() {
      const query = document.getElementById('search').value;
      const mainTable = document.getElementById('main-table');
      const resultsDiv = document.getElementById('results');

      if (query.length > 2) { // Memulai pencarian jika input lebih dari 2 karakter
        mainTable.classList.add('hidden'); // Sembunyikan tabel utama
        mainTable.classList.remove('visible');
        fetch(`function.php?action=search&q=${encodeURIComponent(query)}`)
          .then(response => response.json())
          .then(data => {
            displayResults(data);
          })
          .catch(error => console.error('Error:', error));
      } else {
        mainTable.classList.remove('hidden'); // Tampilkan kembali tabel utama
        mainTable.classList.add('visible');
        resultsDiv.innerHTML = ''; // Bersihkan hasil pencarian
      }
    }

    // Event listener untuk input pencarian
    document.getElementById('search').addEventListener('input', handleSearch);

    // Fungsi untuk menampilkan hasil pencarian
    function displayResults(data) {
      const resultsDiv = document.getElementById('results');
      resultsDiv.innerHTML = '';

      if (data.length > 0) {
        const table = document.createElement('table');
        table.className = 'table app-table-hover mb-0 text-left';

        const thead = document.createElement('thead');
        thead.innerHTML = `
        <tr>
          <th class="cell">No.</th>
          <th class="cell">Distrik</th>
          <th class="cell">Komoditas</th>
          <th class="cell">Luas Tanam <br> (HA)</th>
          <th class="cell">Luas Panen <br> (HA)</th>
          <th class="cell">Data Produksi <br> (KW)</th>
          <th class="cell">Harga Komoditi Tingkat <br> Petani (Minggu)</th>
        </tr>
      `;
        table.appendChild(thead);

        const tbody = document.createElement('tbody');

        data.forEach((item, index) => {
          const row = document.createElement('tr');
          row.innerHTML = `
          <td class="cell">${index + 1}</td>
          <td class="cell">${item.distrik}</td>
          <td class="cell">${item.komoditas}</td>
          <td class="cell">${item.luasLahan}</td>
          <td class="cell">${item.luasPanen}</td>
          <td class="cell">${item.dataProduksi}</td>
          <td class="cell">Rp ${item.hktppm}</td>
        `;
          tbody.appendChild(row);
        });

        table.appendChild(tbody);
        resultsDiv.appendChild(table);
      } else {
        resultsDiv.innerHTML = '<p>No results found</p>';
      }
    }
  </script>

</body>



</html>