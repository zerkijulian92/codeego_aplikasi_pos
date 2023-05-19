<?php
require_once('../koneksi/koneksi.php');

// SIMPAN DATA
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    $insertSQL = sprintf(
        "INSERT INTO kassa (userkassa, pwdkassa, fullname, jkkassa) VALUES (%s, %s, %s, %s)",
        inj($koneksi, $_POST['userkassa'], "text"),
        inj($koneksi, $_POST['pwdkassa'], "text"),
        inj($koneksi, $_POST['fullname'], "text"),
        inj($koneksi, $_POST['jkkassa'], "text")
    );
    $Result1 = mysqli_query($koneksi, $insertSQL) or die(errorQuery(mysqli_error($koneksi)));
}

?>

<h3>INSERT DATA KASSA</h3>
<form action="<?php echo  $editFormAction; ?>" method="post" name="form1">
    <input type="text" name="userkassa" placeholder="Username"><br>
    <input type="password" name="pwdkassa" placeholder="Password"><br>
    <input type="text" name="fullname" placeholder="Nama Lengkap"><br>
    <input type="radio" name="jkkassa" value="L" <?php if (!(strcmp("L", "L"))) {
                                                        echo "CHECKED";
                                                    } ?> />Laki-laki
    <input type="radio" name="jkkassa" value="P" <?php if (!(strcmp("L", "P"))) {
                                                        echo "CHECKED";
                                                    } ?> />Perempuan

    <br>
    <button type="submit">Simpan</button><a href="read.php"> Lihat Data</a>
    <input type="hidden" name="MM_insert" value="form1">
</form>