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

<!-- Fungsi Batal -->
<?php
if ((isset($_GET['id'])) && ($_GET['id'] != "")) {

    $deleteSQL = sprintf(
        "DELETE FROM transaksi_temp WHERE idtr=%s",
        inj($koneksi, $_GET['id'], "int")
    );

    $Result1 = mysqli_query($koneksi, $deleteSQL) or die(errorQuery(mysqli_error($koneksi)));
}

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

<!-- Menampilkan Data Temp Transaksi -->
<?php
$queryTemp = "SELECT * FROM transaksi_temp ORDER BY idtr DESC";
$eksekusiTemp = mysqli_query($koneksi, $queryTemp) or die(errorQuery(mysqli_error($koneksi)));
$rowTemp = mysqli_fetch_assoc($eksekusiTemp);
$totalRowsTemp = mysqli_num_rows($eksekusiTemp);

?>

<!-- Insert Data Transaksi -->
<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_selesai"])) && ($_POST["MM_selesai"] == "selesai")) {
    $insertSQL = "INSERT INTO `transaksi`(`faktur`, `tanggal`, `produk`, `nama_produk`, `harga`, `harga_dasar`, `qty`, `potongan`, `kassa`, `nama_kassa`, `periode`) VALUES";
    foreach ($eksekusiTemp as $data) {
        $insertSQL .= '(' . inj($koneksi, $data['faktur'], "text") . ','
            . inj($koneksi, $data['tanggal'], "date") . ','
            . inj($koneksi, $data['produk'], "text") . ','
            . inj($koneksi, $data['nama_produk'], "text") . ','
            . inj($koneksi, $data['harga'], "double") . ','
            . inj($koneksi, $data['harga_dasar'], "double") . ','
            . inj($koneksi, $data['qty'], "int") . ','
            . inj($koneksi, $data['potongan'], "double") . ','
            . inj($koneksi, $data['kassa'], "int") . ','
            . inj($koneksi, $data['nama_kassa'], "text") . ','
            . inj($koneksi, $data['periode'], "text") . '),';
    }
    $a = substr_replace($insertSQL, ";", -1); //Berfungsi untuk menganti koma di akhir menjdi titik koma
    $Result1 = mysqli_query($koneksi, $a) or die(errorQuery(mysqli_error($koneksi)));

    // Fungsi ketika tombol selesai di tekan maka akan menampilkan alert
    if ($Result1) {
        echo "<script>
            alert('Transaksi berhasil dilakukan!');
            document.location = '?page=transaksi/scan';
        </script>";
    }
}
?>
<!--  -->

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
        $total = 0;
        do { ?>
            <tbody>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $rowTemp['produk'] . " - " . $rowTemp['nama_produk']; ?></td>
                    <td><?php echo $rowTemp['qty']; ?></td>
                    <td>Rp.<?php $price = $rowTemp['qty'] * $rowTemp['harga'];
                            echo number_format($price); ?></td>
                    <td>Rp.<?php echo number_format($rowTemp['potongan']); ?></td>
                    <td><a href="?page=transaksi/scan&id=<?php echo $rowTemp['idtr']; ?>">Batal</a></td>
                </tr>
            </tbody>
        <?php
            $no++;
            $total += $price;
        } while ($rowTemp = mysqli_fetch_assoc($eksekusiTemp)); ?>
    </table>
    <H3>Total Belanja : Rp. <?php echo number_format($total); ?></H3>

    <!-- Menambahkan Button selesai -->
    <form action="<?php echo $editFormAction; ?>" method="post" name="selesai">
        <button type="submit">Selesai</button>
        <input type="hidden" name="MM_selesai" value="selesai">
    </form>

<?php } else { ?>
    Keranjang masih kosong
<?php } ?>
<!--  -->