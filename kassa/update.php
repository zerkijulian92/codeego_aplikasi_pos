<?php
require_once('../koneksi/koneksi.php');

// SIMPAN DATA
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    $updateSQL = sprintf(
        "UPDATE kassa SET userkassa=%s, pwdkassa=%s, fullname=%s, jkkassa=%s WHERE idkassa=%s",
        inj($koneksi, $_POST['userkassa'], "text"),
        inj($koneksi, $_POST['pwdkassa'], "text"),
        inj($koneksi, $_POST['fullname'], "text"),
        inj($koneksi, $_POST['jkkassa'], "text"),
        inj($koneksi, $_POST['idkassa'], "int")
    );
    $Result1 = mysqli_query($koneksi, $updateSQL) or die(errorQuery(mysqli_error($koneksi)));
}
//-----

$id = "-1";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$query = sprintf("SELECT  * FROM kassa WHERE idkassa = %s ORDER BY idkassa ASC", inj($koneksi, $id, "int"));
$eksekusi = mysqli_query($koneksi, $query) or die(errorQuery(mysqli_error($koneksi)));
$row = mysqli_fetch_assoc($eksekusi);
$totalRows = mysqli_num_rows($eksekusi);


?>

<h3>UPDATE DATA KASSA</h3>
<form action="<?php echo  $editFormAction; ?>" method="post" name="form1">
    <input type="text" name="userkassa" value="<?php echo htmlentities($row['userkassa'], ENT_COMPAT, ''); ?>"><br>
    <input type="password" name="pwdkassa" value="<?php echo htmlentities($row['pwdkassa'], ENT_COMPAT, ''); ?>"><br>
    <input type="text" name="fullname" value="<?php echo htmlentities($row['fullname'], ENT_COMPAT, ''); ?>"><br>
    <input type="radio" name="jkkassa" value="L" <?php if (!(strcmp($row['jkkassa'], "L"))) {
                                                        echo "CHECKED";
                                                    } ?> />Laki-laki
    <input type="radio" name="jkkassa" value="P" <?php if (!(strcmp($row['jkkassa'], "P"))) {
                                                        echo "CHECKED";
                                                    } ?> />Perempuan

    <br>
    <button type="submit">Simpan</button><a href="read.php"> Lihat Data</a>
    <input type="hidden" name="MM_update" value="form1">
    <input type="hidden" name="idkassa" value="<?php echo htmlentities($row['idkassa'], ENT_COMPAT, ''); ?>">
</form>