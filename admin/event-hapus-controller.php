<?php
include '../koneksi.php';

$id_event = $_GET['id'];

$sql = "DELETE FROM tiket WHERE id_event = '$id_event'";
mysqli_query($koneksi, $sql);

$sql = "DELETE FROM event WHERE id_event = '$id_event'";
mysqli_query($koneksi, $sql);


mysqli_close($koneksi);
header('location:event.php');
