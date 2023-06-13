<?php
include '../koneksi.php';

$id_pengguna = $_POST['id_pengguna'];
$nama = $_POST['nama'];
$username = $_POST['username'];
$email = $_POST['email'];
$telp = $_POST['telp'];

mysqli_query($koneksi, "UPDATE pengguna SET nama='$nama', username='$username', email='$email', no_telp='$telp' WHERE id_pengguna='$id_pengguna'");

header('location:pengguna.php');
