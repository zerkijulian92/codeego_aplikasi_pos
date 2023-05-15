<?php
// menghubungkan ke file koneksi.php
require_once('../koneksi/koneksi.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
 
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
        $insertSQL = sprintf("INSERT INTO kategori (namakategori, ketkategori) VALUES (%s, %s)",
            inj($koneksi, $_POST['namakategori'], "text"),
	        inj($koneksi, $_POST['ketkategori'], "text"));
        $Result1 = mysqli_query($koneksi, $insertSQL) or die(errorQuery(mysqli_error($koneksi)));
}
?>

<h3>INSERT DATA KATEGORI</h3>
<form action="<?php echo $editFormAction; ?>" name="form1" method="POST">
   <input type="text" name="namakategori" placeholder="Tuliskan nama kategori"><br>
   <input type="text" name="ketkategori" placeholder="Tuliskan keterangan kategori"><br>
   <button type="submit">SIMPAN</button> <a href="read.php">Lihat Data</a>
   <input type="hidden" name="MM_insert" value="form1"><br>

</form>