<?php
require_once('koneksi/koneksi.php');

$query = "SELECT  * FROM kategori ORDER BY idkategori ASC";
$eksekusi = mysqli_query($koneksi, $query) or die(errorQuery(mysqli_error($koneksi)));
$row = mysqli_fetch_assoc($eksekusi);
$totalRows = mysqli_num_rows($eksekusi);

//MENYIMPAN DATA
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    $insertSQL = sprintf(
        "INSERT INTO produk (kodeproduk, kategori, namaproduk, hargadasar, hargajual, stok) VALUES (%s, %s, %s, %s, %s, %s)",
        inj($koneksi, $_POST['kodeproduk'], "text"),
        inj($koneksi, $_POST['kategori'], "int"),
        inj($koneksi, $_POST['namaproduk'], "text"),
        inj($koneksi, $_POST['hargadasar'], "double"),
        inj($koneksi, $_POST['hargajual'], "double"),
        inj($koneksi, $_POST['stok'], "int")
    );
    $Result1 = mysqli_query($koneksi, $insertSQL) or die(errorQuery(mysqli_error($koneksi)));
}
//----


?>


<h3>INSERT DATA PRODUK</h3>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1">
    <input type="text" name="kodeproduk" placeholder="Kode Produk"><br>
    Pilih Kategori
    <select name="kategori">
        <?php do { ?>
            <option value="<?php echo $row['idkategori']; ?>"><?php echo $row['namakategori']; ?></option>
        <?php } while ($row = mysqli_fetch_assoc($eksekusi)); ?>
    </select><br>

    <input type="text" name="namaproduk" placeholder="Nama Produk"><br>
    <input type="text" name="hargadasar" placeholder="Harga Dasar"><br>
    <input type="text" name="hargajual" placeholder="Harga Jual"><br>
    <input type="text" name="stok" placeholder="Stok Barang"><br>
    <button type="submit">Simpan</button><a href="?page=produk/read">Lihat Data</a>
    <input type="hidden" name="MM_insert" value="form1">
</form>