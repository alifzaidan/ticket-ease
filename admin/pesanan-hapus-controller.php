<?php
include '../koneksi.php';

$id_pesanan = $_GET['id'];

$sql = "DELETE FROM pesanan WHERE id_pesanan = '$id_pesanan'";
mysqli_query($koneksi, $sql);

mysqli_close($koneksi);
header('location:pesanan.php');
