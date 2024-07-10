<?php
include "../connection.php";
if (!is_authenticated()) {
  header('Location: /');
  return;
}
if(is_admin()) {
  header('Location: /');
  return;
}
$page = $_GET["page"] ?? 1;
$offset = $page*10 - 10;
$absensi = query_all($conn, 
  "SELECT absensi.tanggal, absensi.waktu_masuk, absensi.selfie_masuk_path, CONCAT(absensi.latitude_masuk, ', ', absensi.longitude_masuk) AS koordinat_masuk, absensi.waktu_pulang, absensi.selfie_pulang_path, CONCAT(absensi.latitude_pulang, ', ', absensi.longitude_pulang) AS koordinat_pulang, karyawan.nama, karyawan.email FROM absensi INNER JOIN karyawan ON karyawan.id=absensi.karyawan_id WHERE absensi.karyawan_id=? ORDER BY absensi.id DESC LIMIT 10 OFFSET ?", "ii", $_SESSION['user']['id'], $offset);
$i = 1;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - User</title>
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
        <li><a href="/user" class="active">Dashboard</a></li>
        <li><a href="/user/riwayat-absensi.php">Riwayat Absensi</a></li>
      </ul>
    </aside>
    <main>
      <h1>Daftar Absensi</h1>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Tanggal</th>
            <th>Waktu Masuk</th>
            <th>Koordinat Masuk</th>
            <th>Foto Masuk</th>
            <th>Waktu Pulang</th>
            <th>Koordinat Pulang</th>
            <th>Foto Pulang</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($absensi as $ab): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= $ab['nama'] ?></td>
            <td><?= $ab['email'] ?></td>
            <td><?= $ab['tanggal'] ?></td>
            <td><?= $ab['waktu_masuk'] ?></td>
            <td><?= $ab['koordinat_masuk'] ?></td>
            <td><?= $ab['selfie_masuk_path'] ?></td>
            <td><?= $ab['waktu_pulang'] ?></td>
            <td><?= $ab['koordinat_pulang'] ?></td>
            <td><?= $ab['selfie_pulang_path'] ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php if($page > 1) {?>
      <a href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $page-1 ?>">Sebelum</a>
      <?php }
      if(count($absensi) >= 10) {?>
      <a href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $page+1 ?>">Selanjutnya</a>
      <?php }?>
    </main>
  </body>
</html>
