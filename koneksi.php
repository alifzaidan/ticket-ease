<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koneksi Database MySQL</title>
</head>

<body>
    <?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "tiket-konser";
    $port = 3307;
    $koneksi = mysqli_connect($host, $username, $password, $database, $port);
    if (!$koneksi) {
        echo "Server not connected";
    }
    ?>
</body>

</html>