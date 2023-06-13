<?php
include '../koneksi.php';

$id_event = $_POST['id_event'];
$nama_event = $_POST['nama_event'];
$harga_awal = $_POST['harga_awal'];
$tanggal = $_POST['tanggal'];
$jam = $_POST['jam'];
$venue = $_POST['venue'];
$deskripsi = $_POST['deskripsi'];
if ($_FILES['gambar']['tmp_name']) {
    $gambar = addslashes(file_get_contents($_FILES['gambar']['tmp_name']));
}

if ($gambar) {
    mysqli_query($koneksi, "UPDATE event SET nama_event='$nama_event', harga_awal='$harga_awal', tanggal='$tanggal', jam='$jam', venue='$venue', deskripsi='$deskripsi', gambar='$gambar' WHERE id_event='$id_event'");
} else {
    mysqli_query($koneksi, "UPDATE event SET nama_event='$nama_event', harga_awal='$harga_awal', tanggal='$tanggal', jam='$jam', venue='$venue', deskripsi='$deskripsi' WHERE id_event='$id_event'");
}

header('location:event.php');
