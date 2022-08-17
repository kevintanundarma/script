<?php

include 'config.php';

error_reporting(0);

session_start();

if (isset($_SESSION['username'])) {
    header("Location: index.php");
}

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($password) {
        $sql = "SELECT * FROM tb_admin_user WHERE username='$username'";
        $result = mysqli_query($conn, $sql);
        if (!$result->num_rows > 0) {
            $sql = "INSERT INTO tb_admin_user (nama, username, password, status)
                    VALUES ('$nama', '$username', '$password', 'user')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                echo "<script>alert('Selamat, registrasi berhasil!'); window.location = './index.php'</script>";
                $nama = "";
                $username = "";
                $_POST['password'] = "";
            } else {
                echo "<script>alert('Terjadi kesalahan.')</script>";
            }
        } else {
            echo "<script>alert('Username Sudah Terdaftar.')</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        <?php include 'style.css'; ?>
    </style>
    <title>Register</title>
</head>

<body>
    <div class="login-register-form">
        <div class="container">
            <form action="" method="POST" class="login-username">
                <p class="login-text">Register</p>
                <div class="input-group">
                    <input type="text" placeholder="Nama" name="nama" value="<?php echo $nama; ?>" required>
                </div>
                <div class="input-group">
                    <input type="text" placeholder="Username" name="username" value="<?php echo $username; ?>" required>
                </div>
                <div class="input-group">
                    <input type="password" placeholder="Password" name="password" value="<?php echo $password; ?>" required>
                </div>
                <div class="input-group">
                    <button name="submit" class="btn">Register</button>
                </div>
                <p class="login-register-text">Anda sudah punya akun? <a href="index.php">Login </a></p>
            </form>
        </div>
    </div>
</body>

</html>