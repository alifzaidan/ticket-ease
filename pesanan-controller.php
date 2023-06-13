<?php
session_start();
include 'koneksi.php';

$id_pengguna   = $_POST['id_pengguna'];
$id_event      = $_POST['id_event'];
$id_tiket1     = isset($_POST['id_tiket1']) ? $_POST['id_tiket1'] : 0;
$jumlah_tiket1 = isset($_POST['jumlah_tiket1']) ? $_POST['jumlah_tiket1'] : 0;
$id_tiket2     = isset($_POST['id_tiket2']) ? $_POST['id_tiket2'] : 0;
$jumlah_tiket2 = isset($_POST['jumlah_tiket2']) ? $_POST['jumlah_tiket2'] : 0;
$id_tiket3     = isset($_POST['id_tiket3']) ? $_POST['id_tiket3'] : 0;
$jumlah_tiket3 = isset($_POST['jumlah_tiket3']) ? $_POST['jumlah_tiket3'] : 0;
$id_tiket4     = isset($_POST['id_tiket4']) ? $_POST['id_tiket4'] : 0;
$jumlah_tiket4 = isset($_POST['jumlah_tiket4']) ? $_POST['jumlah_tiket4'] : 0;
$total_harga   = $_POST['total_harga'];
$tgl_pemesanan = date("Y-m-d");

mysqli_query($koneksi, "INSERT INTO pesanan (id_pengguna, id_event, id_tiket1, jumlah_tiket1, id_tiket2, jumlah_tiket2, id_tiket3, jumlah_tiket3, id_tiket4, jumlah_tiket4, total_harga, tgl_pemesanan) VALUES ('$id_pengguna', '$id_event', '$id_tiket1', '$jumlah_tiket1', '$id_tiket2', '$jumlah_tiket2', '$id_tiket3', '$jumlah_tiket3', '$id_tiket4', '$jumlah_tiket4', '$total_harga', '$tgl_pemesanan')");

$_SESSION['pesan'] = "Pesanan Anda Berhasil Ditambahkan. Silahkan Cek Pesanan Anda di Halaman Riwayat";

header('location:index.php');
