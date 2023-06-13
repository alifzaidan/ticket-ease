<?php
include 'koneksi.php';
session_start();
$username = $_SESSION['username'];
if (!isset($username)) {
    $_SESSION['msg'] = 'anda harus login untuk mengakses halaman ini';
    header('Location: login.php');
}
$safe_username = mysqli_real_escape_string($koneksi, $username);
$result = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username='$safe_username'");
$pengguna = mysqli_fetch_assoc($result);

$id_event = $_POST['id_event'];
$data_event = mysqli_query($koneksi, "SELECT * FROM event WHERE id_event=$id_event");
$event = mysqli_fetch_assoc($data_event);

$nama_event = $event['nama_event'];
$tanggal = date('d F Y', strtotime($event['tanggal']));
$jam = date('H:i', strtotime($event["jam"]));
$gambarSrc = "data:image/*;base64," . base64_encode($event['gambar']);

$data_tiket = mysqli_query($koneksi, "SELECT * FROM tiket WHERE id_event=$id_event");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $nama_event ?></title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/bootstrap-icons/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="style/style.css" />
</head>

<body style="background-color: #EFFEFF;">
    <nav class="navbar navbar-expand-lg border-bottom sticky-top" style="background-color: #304F6D;">
        <div class="container py-1">
            <a class="navbar-brand text-light" href="index.php">TicketEase</a>
            <?php
            if ($_SESSION['username'] == 'admin') :
            ?>
                <button class="btn btn-primary ms-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
                    <i class="bi bi-list"></i>
                </button>
            <?php endif ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-light" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= $pengguna['nama'] ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end mt-3">
                            <li>
                                <p class="h6 m-3 text-center"><?= $pengguna['username'] ?></p>
                            </li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>

                            <?php
                            if ($_SESSION['username'] != 'admin') :
                            ?>
                                <li>
                                    <a class="dropdown-item" href="riwayat.php">Riwayat</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                            <?php endif ?>

                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container" style="min-height: 60vh;">
        <div class="row">
            <div class="col-md-6 col-12">
                <h1 class="fw-bold pt-4 pb-3">Detail Pemesanan</h1>
                <form>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" value="<?= $pengguna['nama'] ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" value="<?= $pengguna['email'] ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="telp" class="form-label">Nomor Telepon</label>
                        <input type="number" class="form-control" id="telp" value="<?= $pengguna['no_telp'] ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="venue" class="form-label">Venue</label>
                        <input type="text" class="form-control" id="venue" value="<?= $event['venue'] ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal Pelaksanaan Event</label>
                        <input type="text" class="form-control" id="tanggal" value="<?= $tanggal ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="jam" class="form-label">Waktu Pelaksanaan Event</label>
                        <input type="text" class="form-control" id="jam" value="<?= $jam ?> WIB" disabled>
                    </div>
                </form>
            </div>
            <div class="col-md-6 col-12">
                <h3 class="fw-bold pt-5"><?= $nama_event ?></h3>

                <img src="<?= $gambarSrc ?>" alt="<?= $nama_event ?>" class="w-100 mt-3 mb-4 rounded-3" />

                <form action="pesanan-controller.php" method="post">
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Kategori</label>
                        </div>
                        <div class="col-2">
                            <label class="form-label">Jumlah</label>
                        </div>
                        <div class="col">
                            <label class="form-label">Harga</label>
                        </div>
                    </div>
                    <?php
                    $i = 1;
                    while ($i <= $_POST['jumlahkategori']) :
                        if ($_POST["kuantitas$i"] > 0) :
                    ?>
                            <div class="row mb-3">
                                <input type="hidden" class="form-control" name="id_tiket<?= $i ?>" value="<?= $_POST["id$i"] ?>">
                                <div class="col">
                                    <input type="text" class="form-control" name="kategori<?= $i ?>" value="<?= $_POST["kategori$i"] ?>" disabled>
                                </div>
                                <div class="col-2">
                                    <input type="number" class="form-control" value="<?= $_POST["kuantitas$i"] ?>" disabled>
                                    <input type="hidden" class="form-control" name="jumlah_tiket<?= $i ?>" value="<?= $_POST["kuantitas$i"] ?>">
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" name="harga<?= $i ?>" value="Rp<?= number_format($_POST["harga$i"], 0, ',', ',') ?>" disabled>
                                </div>

                            </div>
                    <?php
                        endif;
                        $i++;
                    endwhile;
                    ?>
                    <div class="mb-3">
                        <input type="hidden" class="form-control" name="id_pengguna" value="<?= $pengguna['id_pengguna'] ?>">
                        <input type="hidden" class="form-control" name="id_event" value="<?= $id_event ?>">
                        <label for="total" class="form-label">Total</label>
                        <input type="text" class="form-control" value="Rp<?= number_format($_POST["totalKeseluruhan"], 0, ',', ',') ?>" disabled>
                        <input type="hidden" class="form-control" name="total_harga" value="<?= $_POST["totalKeseluruhan"] ?>">
                    </div>
                    <button class="btn d-flex w-100 mt-4 p-3 justify-content-between align-items-center fw-semibold text-white" style="background-color: #E07D54;" type="submit" name="submit">
                        Pesan Sekarang
                        <i class="bi bi-arrow-right me-2 fs-5"></i>
                    </button>
                </form>
            </div>
        </div>


    </main>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Beneran mau keluar?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Pilih "Logout" jika kamu ingin mengakhiri sesimu.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                    <a class="btn btn-dark" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-5 text-light" style="height: 14rem; background-color: #304F6D;">
        <div class="d-flex justify-content-around pt-5">
            <div class="col-md-4 col-5">
                <h1>TicketEase</h1>
                <p>
                    TicketEase is a event discovery with on-demand ticket order services. Now you can buy your tickets easily through TicketEase!
                </p>
            </div>
            <div class="col-md-4 col-5">
                <h3>Discover More!</h3>
            </div>
        </div>
    </footer>

    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>