<?php

include 'config.php';

error_reporting(0);

session_start();

if (isset($_SESSION['username'])) {
    header("Location: cek_login.php");
}
$test1 = '1';
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM tb_admin_user WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);
    if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $row['username'];
        header("Location: cek_login.php");
    } else {
        echo "<script>alert('Username atau Password Anda salah. Silahkan coba lagi!')</script>";
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
    <title>Login</title>
</head>

<body>
    <div class="login-register-form">
        <div class="alert alert-warning" role="alert">
            <?php echo $_SESSION['error'] ?>
        </div>
        
        <div class="container">
            <form action="cek_login.php" method="POST" class="login-username">
                <p class="login-text">Login</p>
                <div class="input-group">
                    <input type="text" placeholder="Username" name="username" value="<?php echo $username; ?>" required>
                </div>
                <div class="input-group">
                    <input type="password" placeholder="Password" name="password" value="<?php echo $password; ?>" required>
                </div>
                <div class="input-group">
                    <button name="submit" class="btn">Login</button>
                </div>
                <p class="login-register-text">Anda belum punya akun? <a href="register.php">Register</a></p>
            </form>
        </div>
    </div>
</body>

</html>