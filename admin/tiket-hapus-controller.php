<?php
include '../koneksi.php';

$id_tiket = $_GET['id'];

$sql = "DELETE FROM tiket WHERE id_tiket = '$id_tiket'";
mysqli_query($koneksi, $sql);

mysqli_close($koneksi);
header('location:tiket.php');
