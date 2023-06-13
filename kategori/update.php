<?php
require_once('koneksi/koneksi.php');

// EDIT DATA
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    $updateSQL = sprintf(
        "UPDATE kategori SET namakategori=%s, ketkategori=%s WHERE idkategori=%s",
        inj($koneksi, $_POST['namakategori'], "text"),
        inj($koneksi, $_POST['ketkategori'], "text"),
        inj($koneksi, $_POST['idkategori'], "int")
    );
    $Result1 = mysqli_query($koneksi, $updateSQL) or die(errorQuery(mysqli_error($koneksi)));
}
// EDIT DATA============================================

$id = "-1";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$query = sprintf("SELECT  * FROM kategori WHERE idkategori = %s ORDER BY idkategori ASC", inj($koneksi, $id, "int"));
$eksekusi = mysqli_query($koneksi, $query) or die(errorQuery(mysqli_error($koneksi)));
$row = mysqli_fetch_assoc($eksekusi);
$totalRows = mysqli_num_rows($eksekusi);


?>

<h3>UPDATE DATA KATEGORI</h3>
<form action="<?php echo $editFormAction; ?>" name="form1" method="POST">
    <input type="text" name="namakategori" value="<?php echo htmlentities($row['namakategori'], ENT_COMPAT, ''); ?>"><br>
    <input type="text" name="ketkategori" value="<?php echo htmlentities($row['ketkategori'], ENT_COMPAT, ''); ?>"><br>
    <button type="submit">SIMPAN</button><a href="?page=kategori/read">Kembali</a>
    <input type="hidden" name="MM_update" value="form1"><br>
    <input type="hidden" name="idkategori" value="<?php echo htmlentities($row['idkategori'], ENT_COMPAT, ''); ?>"><br>

</form>