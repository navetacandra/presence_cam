<?php
include "../../connection.php";
if(!is_admin()) {
  header('Location: /');
  return;
}

$karyawan = query_all($conn, "SELECT id FROM karyawan WHERE id=?", "i", $_GET["id"]);
if(count($karyawan) < 1) {
  echo '<script>alert("Karyawan tidak ditemukan!"); window.location.href="/admin/user/list.php";</script>';
  return;
}

$deleted = query_execute($conn, "DELETE FROM karyawan WHERE id=?", "i", $_GET['id']);
if($deleted) {
  unlink($karyawan[0]['foto_profil']);
  echo '<script>alert("Karyawan berhasil dihapus!"); window.location.href="/admin/user/list.php";</script>';
} else {
  echo '<script>alert("Gagal menghapus karyawan!"); window.location.href="/admin/user/list.php";</script>';
}
