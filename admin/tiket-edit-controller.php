<?php
include '../koneksi.php';

$id_tiket = $_POST['id_tiket'];
$id_event = $_POST['id_event'];
$kategori = $_POST['kategori'];
$harga = $_POST['harga'];
$jumlah = $_POST['jumlah'];

mysqli_query($koneksi, "UPDATE tiket SET id_event='$id_event', kategori='$kategori', harga='$harga', jumlah='$jumlah' WHERE id_tiket='$id_tiket'");

header('location:tiket.php');
