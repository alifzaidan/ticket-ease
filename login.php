<?php
require('koneksi.php');

session_start();
$error = '';

if (isset($_SESSION['username'])) header('Location: index.php');
if (isset($_POST['submit'])) {

    $username = stripslashes($_POST['username']);
    $username = mysqli_real_escape_string($koneksi, $username);
    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($koneksi, $password);

    $query    = "SELECT * FROM pengguna WHERE username = '$username'";
    $result   = mysqli_query($koneksi, $query);
    $rows     = mysqli_num_rows($result);
    if ($rows != 0) {
        $hash   = mysqli_fetch_assoc($result)['password'];
        if (password_verify($password, $hash)) {
            $_SESSION['username'] = $username;
            header('Location: index.php');
        } else {
            $error = 'Password salah';
        }
    } else {
        $error =  'Anda belum daftar';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin - Login</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/bootstrap-icons/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="style/style.css" />

</head>

<body style="background: url(assets/img/bg-login.jpg); background-repeat: no-repeat; background-size: cover; height: 100vh;">
    <div class="container col-md-5 col-12">
        <div class="row justify-content-center m-auto">
            <div class="card border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row h-100">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h3 text-gray-900 mb-4">Selamat Datang!</h1>
                            </div>
                            <?php if ($error != '') { ?>
                                <div class="alert alert-danger" role="alert"><?= $error; ?></div>
                            <?php } ?>
                            <form class="user d-flex flex-column gap-2" action="login.php" method="post">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="exampleFirstName" placeholder="Username" name="username" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" id="exampleInputPassword" placeholder="Password" name="password" required>
                                </div>
                                <button type="submit" name="submit" class="btn p-2 mt-3 fw-semibold text-white" style="background-color: #E07D54;">
                                    Login
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="register.php" style="color: #304F6D;">Daftar Akun!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>