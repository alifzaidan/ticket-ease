<?php
require('koneksi.php');

session_start();
$error = '';
$validate = '';

if (isset($_POST['submit'])) {

    $nama     = stripslashes($_POST['nama']);
    $nama     = mysqli_real_escape_string($koneksi, $nama);
    $username = stripslashes($_POST['username']);
    $username = mysqli_real_escape_string($koneksi, $username);
    $email    = stripslashes($_POST['email']);
    $email    = mysqli_real_escape_string($koneksi, $email);
    $telp     = stripslashes($_POST['telp']);
    $telp     = mysqli_real_escape_string($koneksi, $telp);
    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($koneksi, $password);
    $repass   = stripslashes($_POST['repassword']);
    $repass   = mysqli_real_escape_string($koneksi, $repass);

    if ($password == $repass) {
        if (cek_username($username, $koneksi) == 0) {
            $pass  = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO pengguna (nama,username,email,no_telp, password ) VALUES ('$nama','$username','$email','$telp','$pass')";
            $result   = mysqli_query($koneksi, $query);
            if ($result) {
                $_SESSION['username'] = $username;
                header('Location: index.php');
            } else {
                $error =  'Register User Gagal !!';
            }
        } else {
            $error =  'Username sudah terdaftar !!';
        }
    } else {
        $validate = 'Password tidak sama !!';
    }
}

function cek_username($username, $koneksi)
{
    $cekusername = mysqli_real_escape_string($koneksi, $username);
    $query = "SELECT * FROM pengguna WHERE username = '$cekusername'";
    if ($result = mysqli_query($koneksi, $query)) return mysqli_num_rows($result);
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
    <title>Admin - Register</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/bootstrap-icons/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="style/style.css" />

</head>

<body style="background: url(assets/img/bg-login.jpg); background-repeat: no-repeat; background-size: cover;">
    <div class="container col-md-5 col-12">
        <div class="row justify-content-center m-auto">
            <div class="card border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row h-100">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h3 text-gray-900 mb-4">Register</h1>
                            </div>
                            <?php if ($error != '') { ?>
                                <div class="alert alert-danger" role="alert"><?= $error; ?></div>
                            <?php } ?>
                            <form class="user d-flex flex-column gap-2" action="register.php" method="post">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Username" name="username" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Nama" name="nama" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <input type="number" class="form-control" placeholder="Nomor Telepon" name="telp" required>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control" placeholder="Password" name="password" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control" placeholder="Ulangi Password" name="repassword" required>
                                    </div>
                                </div>
                                <?php if ($validate != '') { ?>
                                    <p class="text-danger"><?= $validate; ?></p>
                                <?php } ?>
                                <button type="submit" name="submit" class="btn p-2 mt-3 fw-semibold text-white" style="background-color: #E07D54;">
                                    Register
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="login.php" style="color: #304F6D;">Sudah punya akun? Login!</a>
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