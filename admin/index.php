<?php
include "../connection.php";
if(!is_admin()) {
  header('Location: /');
  return;
}

$karyawan = query_all($conn, "SELECT COUNT(id) AS jumlah FROM karyawan")[0]['jumlah'];
$absensi_masuk = query_all($conn, "SELECT COUNT(id) AS jumlah FROM absensi WHERE tanggal=?", "s", date('Y-m-d'))[0]['jumlah'];
$absensi_pulang = query_all($conn, "SELECT COUNT(id) AS jumlah FROM absensi WHERE tanggal=? AND waktu_pulang IS NOT NULL", "s", date('Y-m-d'))[0]['jumlah'];
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
      <h1>Dashboard</h1>
      <table>
        <thead>
          <tr>
            <th>Karyawan terdaftar</th>
            <th>Jumlah karyawan absensi masuk</th>
            <th>Jumlah karyawan absensi pulang</th>
          </tr>
        </thead>
        <tbody>
          <tr>
          <td><?= $karyawan ?></td>
          <td><?= $absensi_masuk ?></td>
          <td><?= $absensi_pulang ?></td>
          </tr>
        </tbody>
      </table>
    </main>
  </body>
</html>
