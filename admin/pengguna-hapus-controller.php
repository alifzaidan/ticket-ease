<?php
include '../koneksi.php';

$id_pengguna = $_GET['id'];

$sql = "DELETE FROM pengguna WHERE id_pengguna = '$id_pengguna'";
mysqli_query($koneksi, $sql);

mysqli_close($koneksi);
header('location:pengguna.php');
