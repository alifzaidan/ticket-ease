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
$data_pengguna = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username != 'admin'");
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
            <a href="pengguna.php" class="btn w-100 mb-2 text-white" style="background-color: #304F6D;">Daftar Pengguna</a>
            <a href="pesanan.php" class="btn btn-outline-secondary w-100 mb-2">Daftar Pesanan</a>
        </div>
    </aside>

    <main class="container" style="min-height: 60vh;">
        <h3 class="pt-5">Daftar Pengguna</h3>
        <p class="pb-3">Kelola pengguna yang dapat mengakses websitemu.</p>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Nomor Telepon</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($pengguna = mysqli_fetch_assoc($data_pengguna)) :
                ?>
                    <tr>
                        <td scope="col"><?= $no++ ?></td>
                        <td scope="col"><?= $pengguna["nama"] ?></td>
                        <td scope="col"><?= $pengguna["username"] ?></td>
                        <td scope="col"><?= $pengguna["email"] ?></td>
                        <td scope="col"><?= $pengguna["no_telp"] ?></td>
                        <td scope="col">
                            <div class="d-flex justify-content-around gap-2">
                                <a href="#" class="btn btn-warning btn-circle btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $pengguna["id_pengguna"] ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <div class="modal fade" id="editModal<?= $pengguna["id_pengguna"] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit Data Pengguna</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form class="user d-flex flex-column gap-2" action="pengguna-edit-controller.php" method="post">
                                                    <input type="hidden" class="form-control" name="id_pengguna" value="<?= $pengguna["id_pengguna"] ?>">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="Nama" name="nama" value="<?= $pengguna["nama"] ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="Username" name="username" value="<?= $pengguna["username"] ?>" required>
                                                    </div>
                                                    <div class=" form-group">
                                                        <input type="email" class="form-control" placeholder="Email" name="email" value="<?= $pengguna["email"] ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" placeholder="Nomor Telepon" name="telp" value="<?= $pengguna["no_telp"] ?>" required>
                                                    </div>
                                                    <button type="submit" name="tambah" class="btn btn-primary p-2 mt-3 fw-semibold  text-white" style="background-color: #E07D54;">
                                                        Edit
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-danger btn-circle btn-sm" data-bs-toggle="modal" data-bs-target="#hapusModal<?= $pengguna["id_pengguna"] ?>">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <div class="modal fade" id="hapusModal<?= $pengguna["id_pengguna"] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Yakin mau dihapus?</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">Pilih "Hapus" jika kamu yakin untuk menghapus data "<?= $pengguna["username"] ?>".</div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                                <a class="btn btn-dark" href="pengguna-hapus-controller.php?id=<?= $pengguna["id_pengguna"] ?>">Hapus</a>
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
        function cariPengguna() {
            var input = document.getElementById("searchInput").value.toLowerCase();
            var rows = document.getElementsByTagName("tr");

            for (var i = 1; i < rows.length; i++) {
                var row = rows[i];
                var namaPengguna = row.getElementsByTagName("td")[1].textContent.toLowerCase();

                if (namaPengguna.indexOf(input) > -1) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            }
        }

        document.getElementById("searchInput").addEventListener("input", cariPengguna);
    </script>
</body>

</html>