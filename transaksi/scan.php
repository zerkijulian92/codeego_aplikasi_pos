<?php
// menghubungkan ke file koneksi.php
require_once('koneksi/koneksi.php');

// pencarian data produk
$cari = "-1";
if (isset($_POST['search'])) {
    $cari = $_POST['search'];
}

$query = sprintf(
    "SELECT produk.*, namakategori FROM produk
LEFT JOIN kategori ON idkategori = kategori
WHERE namaproduk LIKE %s OR kodeproduk = %s
ORDER BY idproduk ASC",
    inj($koneksi, $cari, "text"),
    inj($koneksi, $cari, "text")
);

$eksekusi = mysqli_query($koneksi, $query) or die(errorQuery(mysqli_error($koneksi)));
$row = mysqli_fetch_assoc($eksekusi);
$totalRows = mysqli_num_rows($eksekusi);

?>
<!-- // -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Scan</title>
</head>

<body>
    <h3>SCAN HERE</h3>
    <form action="" method="post">
        <label for="cari">Scan Barcode</label>
        <input type="text" name="search" id="cari">
        <button type="submit">search</button>
    </form>

    <!--Periksa apakah data yang dicari ada di di dalam database atau tidak-->
    <?php
    if ($totalRows > 0) {
        echo "Data ditemukan";
    } else {
        echo "Data tidak ditemukan";
    }
    ?>
    <!--  -->

</body>

</html>