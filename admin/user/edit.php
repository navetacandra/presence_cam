<?php
include "../../connection.php";
if(!is_admin()) {
  header('Location: /');
  return;
}
$error = [];
$roles = query_all($conn, "SELECT * FROM roles");
$karyawan = query_all($conn, "SELECT * FROM karyawan WHERE id=?", "i", $_GET["id"]);
if(count($karyawan) < 1) {
  echo '<script>alert("Karyawan tidak ditemukan!"); window.location.href="/admin/user/list.php";</script>';
  return;
}

$karyawan = $karyawan[0];
if($_SERVER["REQUEST_METHOD"] == "POST") {
  $nama = trim($_POST["nama"]);
  $email = trim($_POST["email"]);
  $password = $_POST["password"];
  $role = trim($_POST["role"]);
  $hasFile = false;
  $tmpFile = $_FILES["foto_profil"];
  if(strlen($tmpFile["tmp_name"]) > 0) {
    $tmpFile['mime'] = mime_content_type($tmpFile['tmp_name']);
    $hasFile = true;
  }

  if(empty($nama)) $error["nama"] = "Nama wajib di-isi!";
  if(empty($email)) $error["email"] = "Email wajib di-isi!";
  if(!isset($error['email']) && !filter_var($email, FILTER_VALIDATE_EMAIL)) $error["email"] = "Email harus valid!";
  if(!empty($password) && strlen($password) < 8) $error["password"] = "Password minimal berisi 8 karakter!";
  if(empty($role)) $error["role"] = "Role wajib di-isi!";
  if(!isset($error['role']) && !in_array($role, array_column($roles, 'id'))) $error["role"] = "Role tidak terdaftar!";
  if($hasFile) {
    $allowed = array('jpg', 'jpeg', 'png');
    $type = explode('/', $tmpFile['mime']);
    if($type[0] != "image") $error['foto_profil'] = "File harus berupa gambar!";
    if(!isset($error['foto_profil']) && !in_array($type[1], $allowed)) $error['foto_profil'] = "File harus berupa gambar!";
  }

  $sameEmail = query_all($conn, "SELECT * FROM karyawan WHERE email=? AND id!=?", "si", $email, $karyawan['id']);
  if(count($sameEmail) > 0) $error["email"] = "Email sudah terdaftar!";

  if(count($error) < 1) {
    var_dump($_POST);
    if($hasFile) {
      $path = "/upload/profile/" . uniqid() . "." . $type[1];
      move_uploaded_file($tmpFile['tmp_name'], "../../".$path);
    } else {
      $path = $karyawan['foto_profil'];
    }

    if(!empty($password)) {
      $password = password_hash($password, PASSWORD_BCRYPT);
    } else {
      $password = $karyawan['password'];
    }
    $added = query_execute($conn, 
      "UPDATE karyawan SET nama=?, email=?, password=?, role_id=?, foto_profil=? WHERE id=?", 
      "sssssi", $nama, $email, $password, $role, $path, $karyawan['id']);
    if(!$added) {
      $error["nama"] = "Gagal mengubah info karyawan!";
      unlink($path);
    } else {
      unlink($karyawan['foto_profil']);
      echo '<script>alert("Karyawan berhasil dirubah!")</script>';
      header("Location: /admin/user/list.php");
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Admin</title>
    <link rel="stylesheet" href="/assets/style.css">
  </head>
  <body>
    <nav>
      <a class="brand">Presensi</a>
      <label for="toggle" class="toggler">
        <span></span>
        <span></span>
        <span></span>
      </label>
    </nav>
    <input type="checkbox" name="toggle" id="toggle" style="display: none;">
    <aside>
      <a class="brand" href="/">Presensi</a>
      <label for="toggle" class="close-btn">&times;</label>
      <ul>
        <li><a href="/admin" class="active">Dashboard</a></li>
        <li class="dropdown">
          <a href="#">User</a>
          <ul>
            <li><a href="/admin/user/list.php">List</a></li>
            <li><a href="/admin/user/tambah.php">Tambah</a></li>
          </ul>
        </li>
        <li><a href="/admin/riwayat-absensi.php">Riwayat Absensi</a></li>
      </ul>
    </aside>
    <main>
      <h1>Edit User</h1>
      <form action="<?= $_SERVER['REQUEST_URI'] ?>" enctype="multipart/form-data" method="POST">
        <div class="form-control">
          <label for="nama">Nama</label>
          <input type="text" id="nama" name="nama" required value="<?= post_value_initial('nama', $karyawan) ?>" />
          <?php error_message($error, 'nama') ?>
        </div>
        <div class="form-control">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required value="<?= post_value_initial('email', $karyawan) ?>" />
          <?php error_message($error, 'email') ?>
        </div>
        <div class="form-control">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" />
          <?php error_message($error, 'password') ?>
        </div>
        <div class="form-control">
          <label for="role">Role</label>
          <select name="role" id="role">
            <?php foreach ($roles as $role): ?>
            <option value="<?= $role['id'] ?>" <?php if(post_value_initial('role', $karyawan) == $role['id']) { echo 'selected'; } ?>><?= $role['nama'] ?></option>
            <?php endforeach; ?>
          </select>
          <?php error_message($error, 'role') ?>
        </div>
        <div class="form-control">
          <label for="foto_profil">Foto Profil</label>
          <input type="file" id="foto_profil" name="foto_profil" />
          <?php error_message($error, 'foto_profil') ?>
        </div>
        <div class="form-control">
          <button type="submit">Edit</button>
        </div>
      </form>
    </main>
  </body>
</html>
