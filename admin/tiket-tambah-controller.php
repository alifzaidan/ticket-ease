<?php
include '../koneksi.php';

$id_event = $_POST['id_event'];
$kategori = $_POST['kategori'];
$harga = $_POST['harga'];
$jumlah = $_POST['jumlah'];

mysqli_query($koneksi, "INSERT INTO tiket (id_event, kategori, harga, jumlah) VALUES ('$id_event', '$kategori', '$harga', '$jumlah')");

header('location:tiket.php');
