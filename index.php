<?php
session_start();
$conn = mysqli_connect("localhost","root","","db_parkir");
if(!$conn){ die("Koneksi gagal"); }

if(isset($_POST['login'])){
    $u=$_POST['username'];
    $p=md5($_POST['password']);
    $q=mysqli_query($conn,"SELECT * FROM admin WHERE username='$u' AND password='$p'");
    if(mysqli_num_rows($q)>0){ $_SESSION['login']=true; }
}

if(isset($_GET['logout'])){
    session_destroy();
    header("Location:index.php");
}

if(isset($_POST['masuk'])){
    $plat=$_POST['plat'];
    $jenis=$_POST['jenis'];
    mysqli_query($conn,"INSERT INTO kendaraan VALUES(NULL,'$plat','$jenis',NOW(),NULL,0)");
}

if(isset($_GET['keluar'])){
    $id=$_GET['keluar'];
    $d=mysqli_fetch_assoc(mysqli_query($conn,"SELECT waktu_masuk FROM kendaraan WHERE id_kendaraan=$id"));
    $jam=ceil((time()-strtotime($d['waktu_masuk']))/3600);
    $biaya=$jam*2000;
    mysqli_query($conn,"UPDATE kendaraan SET waktu_keluar=NOW(), biaya=$biaya WHERE id_kendaraan=$id");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Sistem Manajemen Parkir</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary mb-4">
  <div class="container-fluid">
    <span class="navbar-brand">🚗 Sistem Manajemen Parkir</span>
    <?php if(isset($_SESSION['login'])){ ?>
    <a href="?logout=true" class="btn btn-danger btn-sm">Logout</a>
    <?php } ?>
  </div>
</nav>

<div class="container">

<?php if(!isset($_SESSION['login'])){ ?>
<div class="row justify-content-center">
  <div class="col-md-4">
    <div class="card shadow">
      <div class="card-body">
        <h4 class="text-center mb-3">Login Admin</h4>
        <form method="post">
          <input name="username" class="form-control mb-2" placeholder="Username" required>
          <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
          <button name="login" class="btn btn-primary w-100">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php } else { ?>

<div class="row mb-4">
  <div class="col-md-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5>Input Kendaraan</h5>
        <form method="post">
          <input name="plat" class="form-control mb-2" placeholder="Plat Nomor" required>
          <select name="jenis" class="form-control mb-2">
            <option>Motor</option>
            <option>Mobil</option>
          </select>
          <button name="masuk" class="btn btn-success w-100">Kendaraan Masuk</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-8">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5>Data Parkir</h5>
        <table class="table table-striped">
          <tr>
            <th>No</th><th>Plat</th><th>Jenis</th><th>Masuk</th><th>Keluar</th><th>Biaya</th><th>Aksi</th>
          </tr>
          <?php $no=1; $q=mysqli_query($conn,"SELECT * FROM kendaraan ORDER BY id_kendaraan DESC");
          while($r=mysqli_fetch_assoc($q)){ ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= $r['plat_nomor'] ?></td>
            <td><?= $r['jenis_kendaraan'] ?></td>
            <td><?= $r['waktu_masuk'] ?></td>
            <td><?= $r['waktu_keluar'] ?></td>
            <td>Rp <?= number_format($r['biaya']) ?></td>
            <td>
              <?php if($r['waktu_keluar']==NULL){ ?>
              <a href="?keluar=<?= $r['id_kendaraan'] ?>" class="btn btn-warning btn-sm">Keluar</a>
              <?php } ?>
            </td>
          </tr>
          <?php } ?>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="text-center text-muted mt-4">
  <small>UAS Pemrograman Berbasis Web – Teknik Informatika</small>
</div>

<?php } ?>

</div>
</body>
</html>
