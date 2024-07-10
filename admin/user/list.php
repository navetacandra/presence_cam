<?php
include "../../connection.php";
if(!is_admin()) {
  header('Location: /');
  return;
}

$karyawan = query_all($conn, "SELECT karyawan.id, karyawan.nama, karyawan.email, karyawan.foto_profil, roles.nama AS role FROM karyawan INNER JOIN roles ON roles.id=karyawan.role_id");
$i = 1;
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
      <h1>User List</h1>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Profile</th>
            <th>Role</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            <?php foreach($karyawan as $k): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= $k['nama'] ?></td>
              <td><?= $k['email'] ?></td>
              <td><img src="<?= $k['foto_profil'] ?>" width="80" height="80" /></td>
              <td><?= $k['role'] ?></td>
              <td class="action">
                <a href="/admin/user/edit.php?id=<?= $k['id'] ?>">Edit</a>
                <a href="/admin/user/delete.php?id=<?= $k['id'] ?>">Delete</a>
              </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
      </table>
    </main>
  </body>
</html>
