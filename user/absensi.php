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

$error = [];
$absensi = query_all($conn, "SELECT waktu_masuk, waktu_pulang FROM absensi WHERE karyawan_id=? AND tanggal=CURRENT_DATE", "i", $_SESSION['user']['id']);
if($_SERVER['REQUEST_METHOD'] == "POST" && (count($absensi) < 1 || (count($absensi) == 1 && $absensi[0]["waktu_pulang"] == NULL))) {
    $waktu = trim($_POST['waktu']);
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);
    $selfie = $_FILES['selfie'];
    
    if(strlen($selfie['tmp_name']) > 0) {
        $selfie['mime'] = mime_content_type($selfie['tmp_name']);
    }

    function is_decimal($num) {
        return is_numeric($num) && floor($num) != $num;
    }

    if(empty($waktu)) $error['waktu'] = 'Waktu wajib di-isi!';
    if(empty($latitude)) $error['latitude'] = 'Latitude wajib di-isi!';
    if(!isset($error['latitude']) && !is_decimal($latitude)) $error['latitude'] = 'Latitude tidak valid!';
    if(empty($longitude)) $error['longitude'] = 'Longitude wajib di-isi!';
    if(!isset($error['longitude']) && !is_decimal($longitude)) $error['longitude'] = 'Longitude tidak valid!';
    if(!isset($selfie["mime"])) $error["selfie"] = "Wajib menyertakan foto!";
    if(!isset($error["selfie"]) && isset($selfie["mime"]) && $selfie["mime"] != "image/png") $error["selfie"] = "Format foto tidak didukung!";

    if(count($error) < 1) {
        $type = explode('/', $selfie['mime']);
        if(count($absensi) < 1) {
            $path = "/upload/absensi/masuk_" . uniqid() . "." . $type[1];
            move_uploaded_file($selfie['tmp_name'], "../".$path);
            $added = query_execute($conn, "INSERT INTO absensi (karyawan_id, waktu_masuk, selfie_masuk_path, latitude_masuk, longitude_masuk) VALUES (?, ?, ?, ?, ?)", "issdd", $_SESSION["user"]["id"], $waktu, $path, $latitude, $longitude);
            if($added) {
                echo "<script>alert('Absen berhasil!'); window.refresh();</script>";
            } else {
                echo "<script>alert('Absen gagal!');</script>";
            }
        } else if(count($absensi) == 1) {
            $path = "/upload/absensi/pulang_" . uniqid() . "." . $type[1];
            move_uploaded_file($selfie['tmp_name'], "../".$path);
            $added = query_execute($conn, "UPDATE absensi SET waktu_pulang=?, selfie_pulang_path=?, latitude_pulang=?, longitude_pulang=? WHERE karyawan_id=?", "ssddi", $waktu, $path, $latitude, $longitude, $_SESSION["user"]["id"]);
            if($added) {
                echo "<script>alert('Absen berhasil!'); window.refresh();</script>";
            } else {
                echo "<script>alert('Absen gagal!');</script>";
            }
        }
    } else {
        var_dump($error);
        echo "<script>alert('Absen gagal!')</script>";
    }
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
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" style="display: none;">
            <input type="file" name="selfie" id="selfie">
            <input type="hidden" name="waktu">
            <input type="hidden" name="latitude">
            <input type="hidden" name="longitude">
        </form>
        <?php if (count($absensi) < 1) { ?>
            <div style="background-color: #dc3545; color: #fff; width:fit-content; padding: 1rem; border-radius: 5px;">
                <h4>Anda belum melakukan absen kehadiran hari ini.</h4>
            </div>
        <?php } else if (count($absensi) > 0 && $absensi[0]['waktu_pulang'] == NULL) { ?>
            <div style="background-color: #ffc107; color: #000; width:fit-content; padding: 1rem; border-radius: 5px;">
                <h4>Anda belum melakukan absen pulang hari ini.</h4>
            </div>
        <?php } else {?>
            <div style="background-color: #198754; color: #fff; width:fit-content; padding: 1rem; border-radius: 5px;">
                <h4>Anda sudah melakukan absen hari ini.</h4>
            </div>
        <?php } ?>
    </main>
    <script src="/assets/absen.js"></script>
</body>

</html>