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

$id_event = $_GET['id'];
$data_event = mysqli_query($koneksi, "SELECT * FROM event WHERE id_event=$id_event");
$event = mysqli_fetch_assoc($data_event);

$nama_event = $event['nama_event'];
$gambarSrc = "data:image/*;base64," . base64_encode($event['gambar']);

$no = 0;
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

    <main class="container col-md-5 col-12" style="min-height: 60vh;">
        <h1 class="fw-bold pt-5 pb-3"><?= $nama_event ?></h1>

        <img src="<?= $gambarSrc ?>" alt="<?= $nama_event ?>" class="w-100 mt-3 mb-4 rounded-3" />

        <form action="konfirmasi-pesanan.php" method="post">
            <input type="hidden" class="form-control" name="id_event" value="<?= $id_event ?>">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Jenis Kategori</th>
                        <th scope="col">Harga per Tiket</th>
                        <th scope="col">Kuantitas</th>
                        <th scope="col">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($tiket = mysqli_fetch_assoc($data_tiket)) : ?>
                        <tr>
                            <td>
                                <input type="text" class="form-control" value="<?= $tiket['kategori'] ?>" disabled>
                                <input type="hidden" class="form-control" name="kategori<?= ++$no ?>" value="<?= $tiket['kategori'] ?>">
                                <input type="hidden" class="form-control" name="id<?= $no ?>" value="<?= $tiket['id_tiket'] ?>">
                            </td>
                            <td>Rp<?= number_format($tiket["harga"], 0, ',', ',') ?></td>
                            <td><?php if ($tiket['jumlah'] == 0) { ?>
                                    <p class="text-danger">Sold Out</p>
                                    <input type="hidden" class="form-control" name="kuantitas<?= $no ?>" value="0">
                                <?php } else { ?>
                                    <select class="form-select" name="kuantitas<?= $no ?>" id="kuantitas" onchange="updateTotal(this, <?= $tiket['harga'] ?>, <?= $tiket['id_tiket'] ?>)">
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                <?php } ?>
                            </td>
                            <td>
                                <input type="text" class="form-control" id="total<?= $tiket['id_tiket'] ?>" value="Rp0" disabled>
                                <input type="hidden" class="form-control" id="totalHidden<?= $tiket['id_tiket'] ?>" name="harga<?= $no ?>">
                            </td>
                        </tr>
                    <?php endwhile ?>
                    <input type="hidden" class="form-control" name="jumlahkategori" value="<?= $no ?>">
                    <tr class="table-group-divider">
                        <td colspan="3">
                            <h4>Total</h4>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="totalKeseluruhan" value="Rp0" disabled>
                            <input type="hidden" class="form-control" id="totalKeseluruhanHidden" name="totalKeseluruhan">
                        </td>
                    </tr>
                </tbody>
            </table>

            <button class="btn d-flex w-100 p-3 justify-content-between align-items-center fw-semibold text-white" style="background-color: #E07D54;" type="submit" onclick="lanjutkan()">
                Lanjutkan
                <i class="bi bi-arrow-right me-2 fs-5"></i>
            </button>
        </form>

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
        function updateTotal(select, harga, idTiket) {
            const kuantitas = select.value;
            const total = harga * kuantitas;

            const totalElementId = "total" + idTiket;
            const totalElement = document.getElementById(totalElementId);
            totalElement.value = "Rp" + total.toLocaleString();

            const totalElementHiddenId = "totalHidden" + idTiket;
            const totalElementHidden = document.getElementById(totalElementHiddenId);
            totalElementHidden.value = total

            hitungTotalKeseluruhan();
        }

        function hitungTotalKeseluruhan() {
            const totalElements = document.querySelectorAll('[id^="totalHidden"]');
            console.log(totalElements);
            let totalKeseluruhan = 0;

            totalElements.forEach(element => {
                const totalValue = parseInt(element.value.replace(/[^\d]/g, ""));
                if (!isNaN(totalValue)) {
                    totalKeseluruhan += totalValue;
                }
            });

            const totalKeseluruhanElement = document.getElementById("totalKeseluruhan");
            const totalKeseluruhanHiddenElement = document.getElementById("totalKeseluruhanHidden");
            totalKeseluruhanElement.value = "Rp" + totalKeseluruhan.toLocaleString();
            totalKeseluruhanHiddenElement.value = totalKeseluruhan;
        }

        function lanjutkan() {
            document.querySelector("form").submit();
        }
    </script>
</body>

</html>