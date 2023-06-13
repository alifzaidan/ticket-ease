<?php
include '../koneksi.php';

$nama_event = $_POST['nama_event'];
$harga_awal = $_POST['harga_awal'];
$tanggal = $_POST['tanggal'];
$jam = $_POST['jam'];
$venue = $_POST['venue'];
$deskripsi = $_POST['deskripsi'];
$gambar = addslashes(file_get_contents($_FILES['gambar']['tmp_name']));

mysqli_query($koneksi, "INSERT INTO event (nama_event, harga_awal, tanggal, jam, venue, deskripsi, gambar) VALUES ('$nama_event', '$harga_awal', '$tanggal', '$jam', '$venue', '$deskripsi', '$gambar')");

header('location:event.php');
