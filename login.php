<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
    <!-- Fungsi Menampilkan fungsi checkbox pada halaman login -->
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
</head>

<body>
    <h1>Silahkan Login</h1>
    <form action="" method="pos">
        <label for="username">Username</label><br>
        <input type="text" name="" id="username">
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
</body>

</html>