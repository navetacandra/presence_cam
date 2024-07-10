<?php
include "../connection.php";
if (!is_authenticated()) {
    header('Location: /');
    return;
}
if (is_admin()) {
    header('Location: /');
    return;
}

$absensi = query_all($conn, "SELECT waktu_masuk, waktu_pulang FROM absensi WHERE karyawan_id=? AND tanggal=CURRENT_DATE", "i", $_SESSION['user']['id']);
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
        <?php if (count($absensi) < 1) { ?>
            <div style="background-color: #dc3545; color: #fff; width:fit-content; padding: 1rem; border-radius: 5px;">
                <h4>Anda belum melakukan absen kehadiran hari ini. <a href="/user/absensi.php">Lakukan absensi.</a></h4>
            </div>
        <?php } else if (count($absensi) > 0 && $absensi[0]['waktu_pulang'] == NULL) { ?>
            <div style="background-color: #ffc107; color: #000; width:fit-content; padding: 1rem; border-radius: 5px;">
                <h4>Anda belum melakukan absen pulang hari ini. <a href="/user/absensi.php">Lakukan absensi.</a></h4>
            </div>
        <?php } else { ?>
            <div style="background-color: #198754; color: #fff; width:fit-content; padding: 1rem; border-radius: 5px;">
                <h4>Anda sudah melakukan absen hari ini.</h4>
            </div>
        <?php } ?>
    </main>
</body>

</html>