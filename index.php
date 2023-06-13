<?php
include 'koneksi.php';
session_start();
$username = $_SESSION['username'];
if (!isset($username)) {
    $_SESSION['msg'] = 'anda harus login untuk mengakses halaman ini';
    header('Location: login.php');
}
if (isset($_SESSION['pesan'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            ' . $_SESSION['pesan'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    unset($_SESSION['pesan']);
}
$safe_username = mysqli_real_escape_string($koneksi, $username);
$result = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username='$safe_username'");
$pengguna = mysqli_fetch_assoc($result);

$data_event = mysqli_query($koneksi, "SELECT * FROM event");
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
                <form class="d-flex mx-auto" role="search">
                    <input class="form-control me-2" style="width: 500px;" type="search" id="searchInput" placeholder="Cari Event" aria-label="Search" autocomplete="off" />
                </form>
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

    <?php
    if ($_SESSION['username'] == 'admin') :
    ?>
        <aside class="offcanvas offcanvas-start border-top" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel" style="margin-top: 64px; width: 250px;">
            <div class="offcanvas-body">
                <a href="index.php" class="btn w-100 mb-2 text-white" style="background-color: #304F6D;">Dashboard</a>
                <a href="admin/event.php" class="btn btn-outline-secondary w-100 mb-2">Daftar Event</a>
                <a href="admin/tiket.php" class="btn btn-outline-secondary w-100 mb-2">Daftar Tiket</a>
                <a href="admin/pengguna.php" class="btn btn-outline-secondary w-100 mb-2">Daftar Pengguna</a>
                <a href="admin/pesanan.php" class="btn btn-outline-secondary w-100 mb-2">Daftar Pesanan</a>
            </div>
        </aside>
    <?php endif ?>

    <main class="container" style="min-height: 60vh;">
        <h3 class="pt-5">Selamat Datang, <?= $pengguna['nama'] ?></h3>

        <?php
        if ($_SESSION['username'] != 'admin') :
        ?>
            <p class="pb-3">Pilih event yang kamu inginkan</p>
        <?php endif ?>

        <?php
        if ($_SESSION['username'] == 'admin') :
        ?>
            <p class="pb-3">Silahkan kelola event mendatang</p>
        <?php endif ?>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            <?php
            while ($event = mysqli_fetch_assoc($data_event)) :
                $gambarSrc = "data:image/*;base64," . base64_encode($event['gambar']);
            ?>
                <div class="col">
                    <a href="detail-event.php?id=<?= $event["id_event"] ?>" class="card shadow-lg text-decoration-none" style="min-height: 380px;">
                        <img src="<?= $gambarSrc ?>" class="card-img-top" alt="<?= $event["nama_event"] ?>" />
                        <div class="card-body">
                            <h5 class="fw-bold"><?= $event["nama_event"] ?></h5>
                            <p class="card-text m-0" style="font-size: small">Starting from</p>
                            <p class="card-text mb-2 fw-medium">Rp<?= number_format($event["harga_awal"], 0, ',', ',') ?></p>
                            <div style="font-size: small">
                                <p class="m-0"><i class="bi bi-calendar4 me-2"></i><?= date('d F Y', strtotime($event["tanggal"])) ?></p>
                                <p class="m-0"><i class="bi bi-clock me-2"></i><?= date('H:i', strtotime($event["jam"])) ?></p>
                                <p class="m-0"><i class="bi bi-geo-alt me-2"></i><?= $event["venue"] ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endwhile ?>
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
    <script>
        function cariEvents() {
            var input = document.getElementById("searchInput").value.toLowerCase();
            var cards = document.getElementsByClassName("card");

            for (var i = 0; i < cards.length; i++) {
                var card = cards[i];
                var namaEvent = card.getElementsByClassName("fw-bold")[0].textContent.toLowerCase();

                if (namaEvent.indexOf(input) > -1) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            }
        }

        document.getElementById("searchInput").addEventListener("input", cariEvents);
    </script>

</body>

</html>