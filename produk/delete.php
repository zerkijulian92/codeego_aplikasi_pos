<?php

// Memanggil koneksi
require_once('../koneksi/koneksi.php');

if ((isset($_GET['id'])) && ($_GET['id'] != "")) {

  $deleteSQL = sprintf(
    "DELETE FROM produk WHERE idproduk=%s",
    inj($koneksi, $_GET['id'], "int")
  );

  $Result1 = mysqli_query($koneksi, $deleteSQL) or die(errorQuery(mysqli_error($koneksi)));
}

?>

<h3>DATA BERHASIL DIHAPUS</h3>
<a href="read.php">Kembali</a>