<?php
// MEMANGGIL FUNGSI KONEKSI
require_once('../koneksi/koneksi.php');

// MEMANGGIL DATA DI TABEL KATEGORI BERDASARKAN IDKATEGORI
$queryKategori = "SELECT  * FROM kategori ORDER BY idkategori ASC";
$kategori = mysqli_query($koneksi, $queryKategori) or die(errorQuery(mysqli_error($koneksi)));
$rowKategori = mysqli_fetch_assoc($kategori);
$totalRows = mysqli_num_rows($kategori);

// EDIT DATA
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    $updateSQL = sprintf(
        "UPDATE produk SET kodeproduk=%s, kategori=%s, namaproduk=%s, hargadasar=%s, hargajual=%s, stok=%s WHERE idproduk=%s",
        inj($koneksi, $_POST['kodeproduk'], "text"),
        inj($koneksi, $_POST['kategori'], "text"),
        inj($koneksi, $_POST['namaproduk'], "text"),
        inj($koneksi, $_POST['hargadasar'], "double"),
        inj($koneksi, $_POST['hargajual'], "double"),
        inj($koneksi, $_POST['stok'], "int"),
        inj($koneksi, $_POST['idproduk'], "int")
    );
    $Result1 = mysqli_query($koneksi, $updateSQL) or die(errorQuery(mysqli_error($koneksi)));
}
// EDIT DATA============================================

$id = "-1";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$queryProduk = sprintf("SELECT  * FROM produk WHERE idproduk = %s ORDER BY idproduk ASC", inj($koneksi, $id, "int"));
$produk = mysqli_query($koneksi, $queryProduk) or die(errorQuery(mysqli_error($koneksi)));
$rowProduk = mysqli_fetch_assoc($produk);
$totalRows = mysqli_num_rows($produk);


?>


<h3>UPDATE DATA PRODUK</h3>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1">
    <input type="text" name="kodeproduk" value="<?php echo htmlentities($rowProduk['kodeproduk'], ENT_COMPAT, ''); ?>"><br>
    Pilih Kategori
    <select name="kategori">
        <?php do { ?>
            <option value="<?php echo $rowKategori['idkategori']; ?>" <?php if (!(strcmp($rowKategori['idkategori'], htmlentities($rowProduk['kategori'], ENT_COMPAT, 'utf-8')))) {
                                                                            echo "SELECTED";
                                                                        } ?>>
                <?php echo $rowKategori['namakategori']; ?></option>
        <?php } while ($rowKategori = mysqli_fetch_assoc($kategori)); ?>
    </select>
    <br>
    <input type="text" name="namaproduk" value="<?php echo htmlentities($rowProduk['namaproduk'], ENT_COMPAT, ''); ?>"><br>
    <input type="text" name="hargadasar" value="<?php echo htmlentities($rowProduk['hargadasar'], ENT_COMPAT, ''); ?>"><br>
    <input type="text" name="hargajual" value="<?php echo htmlentities($rowProduk['hargajual'], ENT_COMPAT, ''); ?>"><br>
    <input type="text" name="stok" value="<?php echo htmlentities($rowProduk['stok'], ENT_COMPAT, ''); ?>"><br>
    <button type="submit">Simpan</button><a href="read.php">Lihat Data</a>
    <input type="hidden" name="MM_update" value="form1">
    <input type="hidden" name="idproduk" value="<?php echo htmlentities($rowProduk['idproduk'], ENT_COMPAT, ''); ?>">
</form>