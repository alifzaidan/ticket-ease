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

$data_pesanan = mysqli_query($koneksi, "SELECT *, (jumlah_tiket1 + jumlah_tiket2 + jumlah_tiket3 + jumlah_tiket4) AS jumlah_tiket FROM pesanan
JOIN event ON pesanan.id_event = event.id_event
JOIN pengguna ON pesanan.id_pengguna = pengguna.id_pengguna
WHERE pesanan.id_pengguna = " . $pengguna['id_pengguna']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TicketEase - Discover Event</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/bootstrap-icons/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="style/style.css" />
</head>

<body style="background-color: #EFFEFF;">
    <nav class="navbar navbar-expand-lg border-bottom sticky-top" style="background-color: #304F6D;">
        <div class="container py-1">
            <a class="navbar-brand text-white" href="index.php">TicketEase</a>
            <?php
            if ($_SESSION['username'] == 'admin') :
            ?>
                <button class="btn ms-2" style="background-color: #EFFEFF;" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
                    <i class="bi bi-list"></i>
                </button>
            <?php endif ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
        <h3 class="pt-5">Daftar Pesanan Tiketmu</h3>

        <p class="pb-3">Jangan lewatkan event yang sudah kamu pesan</p>

        <?php
        while ($pesanan = mysqli_fetch_assoc($data_pesanan)) :
            $gambarSrc = "data:image/*;base64," . base64_encode($pesanan['gambar']);
        ?>
            <a href="riwayat.detail.php?pesanan=<?= $pesanan["id_pesanan"] ?>" class="card mb-3 w-100 shadow-lg text-decoration-none">
                <div class="row g-0">
                    <div class="col-md-3">
                        <img src="<?= $gambarSrc ?>" class="card-img-top" alt="<?= $pesanan["nama_event"] ?>" />
                    </div>
                    <div class="col-md-9">
                        <div class="card-body">
                            <h4 class="card-title"><?= $pesanan["nama_event"] ?></h4>
                            <p class="card-text m-0"><?= $pesanan["venue"] ?></p>
                            <p class="card-text m-0"><?= date('d F Y', strtotime($pesanan["tanggal"])) ?></p>
                            <div class="d-flex justify-content-between mt-2">
                                <p class="card-text m-0 fw-bold"><?= $pesanan["jumlah_tiket"] ?> Tiket</p>
                                <p class="card-text"><small class="text-body-secondary">Dipesan pada tanggal <?= date('d F Y', strtotime($pesanan["tgl_pemesanan"])) ?></small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        <?php endwhile ?>
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