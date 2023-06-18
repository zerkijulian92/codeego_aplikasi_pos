<?php
// menghubungkan ke file koneksi.php
require_once('koneksi/koneksi.php');

// Menambahkan Fungsi Logout
//initialize the session
if (!isset($_SESSION)) {
    session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF'] . "?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) {
    $logoutAction .= "&" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) && ($_GET['doLogout'] == "true")) {
    //to fully log out a visitor we need to clear the session varialbles
    $_SESSION['MM_Username'] = NULL;
    $_SESSION['MM_UserGroup'] = NULL;
    $_SESSION['PrevUrl'] = NULL;
    unset($_SESSION['MM_Username']);
    unset($_SESSION['MM_UserGroup']);
    unset($_SESSION['PrevUrl']);

    $logoutGoTo = "login.php";
    if ($logoutGoTo) {
        header("Location: $logoutGoTo");
        exit;
    }
}
?>

<!-- **Menambahkan fungsi protect Dashboard supaya tidak bisa di akses
sebelum melakukan login
 -->
<?php
if (!isset($_SESSION)) {
    session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup)
{
    // For security, start by assuming the visitor is NOT authorized. 
    $isValid = False;

    // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
    // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
    if (!empty($UserName)) {
        // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
        // Parse the strings into arrays. 
        $arrUsers = Explode(",", $strUsers);
        $arrGroups = Explode(",", $strGroups);
        if (in_array($UserName, $arrUsers)) {
            $isValid = true;
        }
        // Or, you may restrict access to only certain users based on their username. 
        if (in_array($UserGroup, $arrGroups)) {
            $isValid = true;
        }
        if (($strUsers == "") && true) {
            $isValid = true;
        }
    }
    return $isValid;
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("", $MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {
    $MM_qsChar = "?";
    $MM_referrer = $_SERVER['PHP_SELF'];
    if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
    if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0)
        $MM_referrer .= "?" . $QUERY_STRING;
    $MM_restrictGoTo = $MM_restrictGoTo . $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
    header("Location: " . $MM_restrictGoTo);
    exit;
}

?>

<!-- MEMNAMPILKAN NAMA BERDASARKAN USER YANG LOGIN -->
<!-- Note -->
<!-- 
Menambahkan script dibawah, dan jangan lupa tambahkan
require_once('koneksi/koneksi.php'); // Untuk memanggil file koneksi.php

 -->
<?php
$id = "-1";
if (isset($_SESSION['MM_Username'])) {
    $id = $_SESSION['MM_Username'];
}

$Login = sprintf(
    "SELECT * FROM kassa WHERE userkassa = %s",
    inj($koneksi, $id, "text")
);

$AksiLogin = mysqli_query($koneksi, $Login) or die(errorQuery(mysqli_error($koneksi)));
$rowLogin = mysqli_fetch_assoc($AksiLogin);
$totalRows = mysqli_num_rows($AksiLogin);

?>
<!-- // -->

<h3>Selamat Datang, <?php echo $rowLogin['fullname']; ?></h3>

<a href="?page=kassa/read">Kassa</a> |
<a href="?page=kategori/read">Kategori</a> |
<a href="?page=produk/read">Produk</a> |
<a href="<?php echo $logoutAction; ?>">Logout</a>

<?php
if (isset($_GET["page"]) && $_GET["page"] != "home") {
    if (file_exists(htmlentities($_GET["page"]) . ".php")) {
        include(htmlentities($_GET["page"]) . ".php");
    } else {
        include("404.php");
    }
} else {
    include("transaksi/scan.php");
}
?>