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

<!--Periksa apakah data yang dicari ada di di dalam database atau tidak-->
<?php
$faktur = "000001";
$periode = "2020";
if ($totalRows > 0) {

    //Cek Data Produk
    $queryCek = sprintf(
        "SELECT produk FROM transaksi_temp WHERE produk = %s AND faktur = %s",
        inj($koneksi, $cari, "text"),
        inj($koneksi, $faktur, "text")
    );
    $cek = mysqli_query($koneksi, $queryCek) or die(errorQuery(mysqli_error($koneksi)));
    $Rows = mysqli_num_rows($cek);
    //--

    // Aksi
    if ($Rows > 0) {
        //Jika data ditemukan
        $updateQty = sprintf(
            "UPDATE transaksi_temp SET qty = qty + 1 WHERE produk = %s AND faktur = %s",
            inj($koneksi, $cari, "text"),
            inj($koneksi, $faktur, "text")
        );
        $resultQty = mysqli_query($koneksi, $updateQty) or die(errorQuery(mysqli_error($koneksi)));
    } else {
        // Menyimpan data ke transaksi_temp
        $insertSQL = sprintf(
            "INSERT INTO `transaksi_temp`(`faktur`, `tanggal`, `produk`, `nama_produk`, `harga`, `harga_dasar`, `qty`, `potongan`, `kassa`, `nama_kassa`, `periode`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
            inj($koneksi, $faktur, "text"),
            inj($koneksi, date("Y-m-d"), "date"),
            inj($koneksi, $row['kodeproduk'], "text"),
            inj($koneksi, $row['namaproduk'], "text"),
            inj($koneksi, $row['hargajual'], "double"),
            inj($koneksi, $row['hargadasar'], "double"),
            inj($koneksi, 1, "int"),
            inj($koneksi, 0, "double"),
            inj($koneksi, $rowLogin['idkassa'], "int"),
            inj($koneksi, $rowLogin['fullname'], "text"),
            inj($koneksi, $periode, "text")
        );
        $Result1 = mysqli_query($koneksi, $insertSQL) or die(errorQuery(mysqli_error($koneksi)));
    } // Simpan data
} else {
    // echo "Data tidak ditemukan";
}
?>
<!--  -->


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Scan</title>
</head>

<body>

    <!-- Menampilkan Data Temp Transaksi -->
    <?php
    $queryTemp = "SELECT * FROM transaksi_temp ORDER BY idtr DESC";
    $eksekusiTemp = mysqli_query($koneksi, $queryTemp) or die(errorQuery(mysqli_error($koneksi)));
    $rowTemp = mysqli_fetch_assoc($eksekusiTemp);
    $totalRowsTemp = mysqli_num_rows($eksekusiTemp);

    ?>

    <h3>SCAN HERE</h3>
    <form action="" method="post">
        <label for="cari">Scan Barcode</label>
        <input type="text" name="search" id="cari">
        <button type="submit">search</button>
    </form>
    <br>
    <!--  -->
    <?php if ($totalRowsTemp) { ?>
        <table border="1">
            <thead>
                <tr>
                    <td>NO</td>
                    <td>PRODUK</td>
                    <td>QTY</td>
                    <td>PRICE</td>
                    <td>POTONGAN</td>
                    <td>ACTION</td>
                </tr>
            </thead>
            <?php $no = 1;
            do { ?>
                <tbody>
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $rowTemp['produk'] . " - " . $rowTemp['nama_produk']; ?></td>
                        <td><?php echo $rowTemp['qty']; ?></td>
                        <td><?php echo $rowTemp['harga']; ?></td>
                        <td><?php echo $rowTemp['potongan']; ?></td>
                        <td>Batal</td>
                    </tr>
                </tbody>
            <?php
                $no++;
            } while ($rowTemp = mysqli_fetch_assoc($eksekusiTemp)); ?>
        </table>
    <?php } else { ?>
        Keranjang masih kosong
    <?php } ?>
    <!--  -->

</body>

</html>