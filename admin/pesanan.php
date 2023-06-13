<?php
include '../koneksi.php';
session_start();
$username = $_SESSION['username'];
if (!isset($username)) {
    $_SESSION['msg'] = 'anda harus login untuk mengakses halaman ini';
    header('Location: login.php');
}
$safe_username = mysqli_real_escape_string($koneksi, $username);
$result = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username='$safe_username'");
$pengguna = mysqli_fetch_assoc($result);

$no = 1;
$data_pesanan = mysqli_query($koneksi, "SELECT *, (jumlah_tiket1 + jumlah_tiket2 + jumlah_tiket3 + jumlah_tiket4) AS jumlah_tiket FROM pesanan
JOIN event ON pesanan.id_event = event.id_event
JOIN pengguna ON pesanan.id_pengguna = pengguna.id_pengguna");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin - TicketEase</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/bootstrap-icons/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="../style/style.css" />
</head>

<body style="background-color: #EFFEFF;">
    <nav class="navbar navbar-expand-lg border-bottom sticky-top" style="background-color: #304F6D;">
        <div class="container py-1">
            <a class="navbar-brand text-white" href="../index.php">TicketEase</a>
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

    <aside class="offcanvas offcanvas-start border-top" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel" style="margin-top: 64px; width: 250px;">
        <div class="offcanvas-body">
            <a href="../index.php" class="btn btn-outline-secondary w-100 mb-2">Dashboard</a>
            <a href="event.php" class="btn btn-outline-secondary w-100 mb-2">Daftar Event</a>
            <a href="tiket.php" class="btn btn-outline-secondary w-100 mb-2">Daftar Tiket</a>
            <a href="pengguna.php" class="btn btn-outline-secondary w-100 mb-2">Daftar Pengguna</a>
            <a href="pesanan.php" class="btn w-100 mb-2 text-white" style="background-color: #304F6D;">Daftar Pesanan</a>
        </div>
    </aside>

    <main class="container" style="min-height: 70vh;">
        <h3 class="pt-5">Daftar Pesanan</h3>
        <p class="pb-3">Kelola pesanan pengguna.</p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">ID Pesanan</th>
                    <th scope="col">Nama Pelanggan</th>
                    <th scope="col">Nama Event</th>
                    <th scope="col">Kategori Tiket</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Total Harga</th>
                    <th scope="col">Tanggal Pemesanan</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($pesanan = mysqli_fetch_assoc($data_pesanan)) :
                ?>
                    <tr>
                        <td scope="col"><?= $no++ ?></td>
                        <td scope="col"><?= $pesanan['id_pesanan'] ?></td>
                        <td scope="col"><?= $pesanan['nama'] ?></td>
                        <td scope="col"><?= $pesanan['nama_event'] ?></td>
                        <td scope="col">
                            <?php
                            $i = 1;
                            $data = [];
                            while ($i <= 4) :
                                if ($pesanan["jumlah_tiket$i"] > 0) :
                                    $data_tiket = mysqli_query($koneksi, "SELECT * FROM tiket,pesanan WHERE tiket.id_tiket='" . $pesanan["id_tiket$i"] . "'");
                                    $tiket = mysqli_fetch_assoc($data_tiket);
                                    $data[] = $tiket["kategori"] . " (" . $pesanan["jumlah_tiket$i"] . ")";
                                endif;
                                $i++;
                            endwhile;
                            echo implode(", ", $data);
                            ?>
                        </td>
                        <td scope="col"><?= $pesanan['jumlah_tiket'] ?></td>
                        <td scope="col">Rp<?= number_format($pesanan["total_harga"], 0, ',', ',') ?></td>
                        <td scope="col"><?= date('d F Y', strtotime($pesanan["tgl_pemesanan"])) ?></td>
                        <td scope="col">
                            <div class="d-flex justify-content-around gap-2">
                                <a href="#" class="btn btn-danger btn-circle btn-sm" data-bs-toggle="modal" data-bs-target="#hapusModal<?= $pesanan["id_pesanan"] ?>">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <div class="modal fade" id="hapusModal<?= $pesanan["id_pesanan"] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Yakin mau dihapus?</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">Pilih "Hapus" jika kamu yakin untuk menghapus pesanan <?= $pesanan["nama"] ?> untuk event "<?= $pesanan["nama_event"] ?>".</div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                                <a class="btn btn-dark" href="pesanan-hapus-controller.php?id=<?= $pesanan["id_pesanan"] ?>">Hapus</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
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
                    <a class="btn btn-dark" href="../logout.php">Logout</a>
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

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        function cariPesanan() {
            var input = document.getElementById("searchInput").value.toLowerCase();
            var rows = document.getElementsByTagName("tr");

            for (var i = 1; i < rows.length; i++) {
                var row = rows[i];
                var namaPengguna = row.getElementsByTagName("td")[2].textContent.toLowerCase();

                if (namaPengguna.indexOf(input) > -1) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            }
        }

        document.getElementById("searchInput").addEventListener("input", cariPesanan);
    </script>
</body>

</html>