<!-- Memanggil Fungsi Koneksi.php -->
<?php require_once('koneksi/koneksi.php'); ?>

<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
    session_start();
    if (isset($_SESSION['MM_Username'])) {
        $halaman = "dashboard.php";
        header("Location: " . $halaman);
        exit;
    }
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
    $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
    $loginUsername = $_POST['username'];
    $password = $_POST['password'];
    $MM_fldUserAuthorization = "";
    $MM_redirectLoginSuccess = "dashboard.php";
    $MM_redirectLoginFailed = "login.php";
    $MM_redirecttoReferrer = true;
    mysqli_select_db($koneksi, $database_koneksi);

    $LoginRS__query = sprintf(
        "SELECT userkassa, pwdkassa FROM kassa WHERE userkassa=%s AND pwdkassa=%s",
        inj($koneksi, $loginUsername, "text"),
        inj($koneksi, $password, "text")
    );

    $LoginRS = mysqli_query($koneksi, $LoginRS__query) or die(mysqli_error($koneksi));
    $loginFoundUser = mysqli_num_rows($LoginRS);
    if ($loginFoundUser) {
        $loginStrGroup = "";

        //declare two session variables and assign them
        $_SESSION['MM_Username'] = $loginUsername;
        $_SESSION['MM_UserGroup'] = $loginStrGroup;

        if (isset($_SESSION['PrevUrl']) && true) {
            $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];
        }
        header("Location: " . $MM_redirectLoginSuccess);
    } else {
        header("Location: " . $MM_redirectLoginFailed);
    }
}
?>

<!-- Fungsi Menampilkan checkbox hide password pada halaman login -->
<script>
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById("password");
        var toggleCheckbox = document.getElementById("toggle");

        if (toggleCheckbox.checked) {
            passwordInput.type = "text";
        } else {
            passwordInput.type = "password";
        }
    }
</script>

<h1>Silahkan Login</h1>
<form action="<?php echo $loginFormAction; ?>" method="post" name="login">
    <label for="username">Username</label><br>
    <input type="text" name="username" id="username">
    <br>
    <label for="password">Password</label><br>
    <input type="password" name="password" id="password">
    <br>
    <label>
        <input type="checkbox" id="toggle" onclick="togglePasswordVisibility()">Show Password
    </label>
    <br>
    <button type="submit">Login</button>
</form>