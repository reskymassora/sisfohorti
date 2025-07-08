-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Jul 2025 pada 08.23
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u832397905_simfonidb`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `fullname` varchar(60) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `fotoProfil` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`fullname`, `email`, `password`, `fotoProfil`) VALUES
('Distanbun Kab. Mimika', 'hortidate@gmail.com', '$2y$10$RnJqWiPRlpmDwp8MYBqhp./l7GHwSz6oK19VBY72u7ZJGFTlFN3/6', '958ec545c3002010b62066885434d943.jpeg'),
('Seprianus Resky Massora', 'reskymassora@gmail.com', '$2y$10$4HNrlj8LGgbygri2LCzitub/XYyC0T9ieiYrJPrOrAWwPAIzUTDxa', 'd915c3d120d8a35a95c4d9520004f297.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `datatanaman`
--

CREATE TABLE `datatanaman` (
  `id` int(11) NOT NULL,
  `distrik` varchar(255) NOT NULL,
  `komoditas` varchar(255) NOT NULL,
  `luasLahan` varchar(100) NOT NULL,
  `luasTanamAkhirBulanLalu` varchar(50) NOT NULL,
  `luasPanenHabisDiBongkar` varchar(100) NOT NULL,
  `luasPanenBelumHabis` varchar(50) NOT NULL,
  `luasRusak` varchar(50) NOT NULL,
  `luasPenanamanBaru` varchar(50) NOT NULL,
  `luasTanamAkhirBulanLaporan` varchar(50) NOT NULL,
  `dataProduksiDiPanenHabis` varchar(50) NOT NULL,
  `dataProduksiBelumHabis` varchar(50) NOT NULL,
  `hktppm` varchar(255) NOT NULL,
  `tanggal` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `datatanaman`
--

INSERT INTO `datatanaman` (`id`, `distrik`, `komoditas`, `luasLahan`, `luasTanamAkhirBulanLalu`, `luasPanenHabisDiBongkar`, `luasPanenBelumHabis`, `luasRusak`, `luasPenanamanBaru`, `luasTanamAkhirBulanLaporan`, `dataProduksiDiPanenHabis`, `dataProduksiBelumHabis`, `hktppm`, `tanggal`) VALUES
(189, 'MIMIKA BARAT', 'LABU SIAM', '5', '5', '5', '5', '5', '5', '5', '5', '5', '30000', '2025-07-08');

--
-- Trigger `datatanaman`
--
DELIMITER $$
CREATE TRIGGER `after_dataTanaman_delete` AFTER DELETE ON `datatanaman` FOR EACH ROW BEGIN
    DECLARE total DECIMAL(10,2);
    
    -- Hitung total dataProduksi untuk komoditas yang dihapus
    SET total = (SELECT CAST(SUM(dataProduksiDiPanenHabis) AS DECIMAL(10,2))
                 FROM dataTanaman WHERE komoditas = OLD.komoditas);
    
    -- Jika totalDataProduksi adalah nol atau NULL, hapus entri komoditas dari totalProduksiKomoditi
    IF total IS NULL OR total = 0 THEN
        DELETE FROM totalProduksiKomoditi WHERE daftarKomoditas = OLD.komoditas;
    -- Jika masih ada dataProduksi, update totalDataProduksi
    ELSE
        UPDATE totalProduksiKomoditi
        SET totalDataProduksi = total
        WHERE daftarKomoditas = OLD.komoditas;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_dataTanaman_insert` AFTER INSERT ON `datatanaman` FOR EACH ROW BEGIN
    DECLARE total DECIMAL(10,2);
    
    -- Hitung total dataProduksi untuk komoditas yang baru diinput
    SET total = (SELECT CAST(SUM(dataProduksiDiPanenHabis) AS DECIMAL(10,2))
                 FROM dataTanaman WHERE komoditas = NEW.komoditas);
    
    -- Jika komoditas sudah ada, update totalDataProduksi
    IF EXISTS (SELECT 1 FROM totalProduksiKomoditi WHERE daftarKomoditas = NEW.komoditas) THEN
        UPDATE totalProduksiKomoditi
        SET totalDataProduksi = total
        WHERE daftarKomoditas = NEW.komoditas;
    -- Jika komoditas belum ada, tambahkan entri baru
    ELSE
        INSERT INTO totalProduksiKomoditi (daftarKomoditas, totalDataProduksi)
        VALUES (NEW.komoditas, total);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_dataTanaman_update` AFTER UPDATE ON `datatanaman` FOR EACH ROW BEGIN
    DECLARE total DECIMAL(10,2);
    
    -- Hitung total dataProduksi untuk komoditas yang diperbarui
    SET total = (SELECT CAST(SUM(dataProduksiDiPanenHabis) AS DECIMAL(10,2))
                 FROM dataTanaman WHERE komoditas = NEW.komoditas);
    
    -- Update totalDataProduksi untuk komoditas yang diperbarui
    UPDATE totalProduksiKomoditi
    SET totalDataProduksi = total
    WHERE daftarKomoditas = NEW.komoditas;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `komentar`
--

CREATE TABLE `komentar` (
  `id` int(11) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `komentar` text NOT NULL,
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `totalproduksikomoditi`
--

CREATE TABLE `totalproduksikomoditi` (
  `daftarKomoditas` varchar(255) NOT NULL,
  `totalDataProduksi` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `totalproduksikomoditi`
--

INSERT INTO `totalproduksikomoditi` (`daftarKomoditas`, `totalDataProduksi`) VALUES
('ANGGUR', '5.00');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `datatanaman`
--
ALTER TABLE `datatanaman`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `komentar`
--
ALTER TABLE `komentar`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `datatanaman`
--
ALTER TABLE `datatanaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT untuk tabel `komentar`
--
ALTER TABLE `komentar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
