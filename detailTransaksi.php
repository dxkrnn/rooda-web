<?php
include 'config.php';
include 'functions.php';

error_reporting(0);

session_start();
if (!isset(($_SESSION['username']))) {
  header("Location:index");
  exit();
}
//Get ID from POST
$id_transaksi = $_POST['id_transaksi'];
$tgl_transaksi = $_POST['tgl_transaksi'];
$nama_pelanggan = $_POST['nama_pelanggan'];
$nik = $_POST['nik'];
$alamat_pelanggan = $_POST['alamat_pelanggan'];
$telp_pelanggan = $_POST['telp_pelanggan'];
$nama_karyawan = $_POST['nama_karyawan'];
$telp_karyawan = $_POST['telp_karyawan'];

$total_transaksi = 0;

if (isset($_POST['submitUbahStatus'])) {
  $id_transaksi = $_POST['id_transaksi'];
  $id_motor = $_POST['id_motor'];
  $status = $_POST['status'];

  $ubahStatus = mysqli_query($conn, "UPDATE tb_detail_transaksi SET status='$status' WHERE id_transaksi='$id_transaksi' AND id_motor='$id_motor'");

  if ($ubahStatus) {
    header('refresh:0; url=transaksiOffline');
    echo "<script>alert('Yeay, Ubah Status berhasil!')</script>";
  } else {
    echo "<script>alert('Yeay, Ubah Status berhasil!')</script>";
  }
}

if (isset($_POST['submitHapusDetail'])) {

  $id_transaksi = $_POST['id_transaksi'];
  $id_motor = $_POST['id_motor'];

  $hapusDetail = mysqli_query($conn, "DELETE FROM tb_detail_transaksi WHERE id_transaksi='$id_transaksi' AND id_motor='$id_motor'");
  if ($hapusDetail) {
    header('refresh:0; url=transaksiOffline');
    echo "<script>alert('Yeay, hapus detail berhasil!')</script>";
  } else {
    echo "<script>alert('Yeay, hapus detail gagal!')</script>";
  }
}

?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Detail Transaksi - Rooda</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="assets/img/favicon/icon_favicon.png" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

  <!-- Icons. Uncomment required icon fonts -->
  <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="assets/css/demo.css" />
  <link rel="stylesheet" href="assets/css/print.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

  <link rel="stylesheet" href="assets/vendor/libs/apex-charts/apex-charts.css" />
  <script src="assets/vendor/js/helpers.js"></script>
  <script src="assets/js/config.js"></script>
  <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>


</head>

<body>
  <!-- Layout wrapper -->
  <div class="card">
    <div class="card-body">
      <div class="container mb-5 mt-3">
        <div class="row d-flex align-items-baseline">
          <div class="col-xl-9 mb-4">
            <img src="assets/img/logo/logo_rooda.png" width="100">
          </div>
          <div class="col-xl-9">
            <p style="color: #7e8d9f;font-size: 20px;">Transaksi#<strong><?= $id_transaksi ?></strong></p>
          </div>
          <hr>
        </div>

        <div class="container">
          <div class="col-md-12">
            <div class="text-center">
              <i class="fab fa-mdb fa-4x ms-0" style="color:#5d9fc5 ;"></i>
              <p class="pt-0"><b>Rooda</b> - <?= tanggal($tgl_transaksi) ?></p>
            </div>

          </div>


          <div class="row">
            <div class="col-xl-8">
              <ul class="list-unstyled">
                <li class="text-muted">Pelanggan:</li>
                <li class="text-muted"><i class="fas fa-phone"></i><?= $nama_pelanggan ?></li>
                <li class="text-muted"><i class="fas fa-phone"></i><?= $alamat_pelanggan ?></li>
                <li class="text-muted"><i class="fas fa-phone"></i>0<?= $telp_pelanggan ?></li>
              </ul>
            </div>
            <div class="col-xl-4">
              <ul class="list-unstyled">
                <li class="text-muted">Sales:</li>
                <li class="text-muted"><i class="fas fa-phone"></i><?= $nama_karyawan ?></li>
                <li class="text-muted"><i class="fas fa-phone"></i>0<?= $telp_karyawan ?></li>
              </ul>
            </div>
          </div>

          <div class="row my-2 mx-1 justify-content-center">
            <table class="table table-striped table-borderless">
              <thead style="background-color:#4AD193 ;" class="text-white">
                <tr>
                  <th scope="col">Nama Motor</th>
                  <th scope="col">Harga</th>
                  <th scope="col">Jumlah</th>
                  <th scope="col">Total</th>
                  <th scope="col">Status</th>
                </tr>
              </thead>
              <tbody>

                <?php
                $ambil_data_detail = mysqli_query(
                  $conn,
                  "SELECT dt.jumlah, dt.status, dt.id_motor, mt.nama AS nama_motor, mt.harga
                  FROM tb_detail_transaksi dt
                  JOIN tb_motor mt
                  USING(id_motor)
                  WHERE id_transaksi='$id_transaksi'
                "
                );

                while ($data = mysqli_fetch_array($ambil_data_detail)) {
                  $id_motor = $data['id_motor'];
                  $nama_motor = $data['nama_motor'];
                  $harga = $data['harga'];
                  $jumlah = $data['jumlah'];
                  $status = $data['status'];
                  $total = $jumlah * $harga;
                  $total_transaksi = $total_transaksi + $total;
                ?>

                  <tr>
                    <td><?= $nama_motor ?></td>
                    <td><?= rupiah($harga) ?></td>
                    <td><?= $jumlah ?></td>
                    <td><?= rupiah($total) ?></td>
                    <td><?= $status ?></td>
                  </tr>
                <?php
                }
                ?>

              </tbody>

            </table>
          </div>
          <hr>
          <div class="row d-flex flex-row-reverse bd-highlight">
            <div class="col-xl-4">
              <p class="text-black float-start"><span class="text-black me-3"> Total Transaksi</span><span>
                  <h4><?= rupiah($total_transaksi) ?></h4>
                </span></p>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-xl-10">
              <p>Terimakasih atas kepercayaan Anda :)</p>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  <!-- / Layout wrapper -->

  <!-- <script>
    window.print();
  </script> -->

  <script>
    $(document).ready(function() {
      $('#example').DataTable({
        // scrollX: true,
      });
    });
  </script>

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  <script src="assets/vendor/libs/jquery/jquery.js"></script>
  <script src="assets/vendor/libs/popper/popper.js"></script>
  <script src="assets/vendor/js/bootstrap.js"></script>
  <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>


  <script src="assets/vendor/js/menu.js"></script>
  <!-- endbuild -->

  <!-- Vendors JS -->
  <script src="assets/vendor/libs/apex-charts/apexcharts.js"></script>

  <!-- Main JS -->
  <script src="assets/js/main.js"></script>

  <!-- Page JS -->
  <script src="assets/js/dashboards-analytics.js"></script>

  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css">

  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>

</body>

</html>