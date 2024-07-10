<?php
include '../connection.php';

if(is_authenticated()) {
  header("Location: /index.php");
  return;
}

$error = [];
if($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  if(empty($email)) $error["email"] = "Email wajib di-isi!";
  if(!isset($error['email']) && !filter_var($email, FILTER_VALIDATE_EMAIL)) $error["email"] = "Email harus valid!";
  if(empty($password)) $error["password"] = "Password wajib di-isi!";
  if(!isset($error['password']) && strlen($password) < 8) $error["password"] = "Password minimal berisi 8 karakter!";

  if(count($error) < 1) {
    $user = query_all($conn, "SELECT karyawan.*, roles.nama=\"admin\" AS is_admin FROM karyawan INNER JOIN roles ON roles.id=karyawan.role_id WHERE email=? LIMIT 1", "s", $email);
    if(count($user) < 1) {
      $error["email"] = "Email atau password salah!";
    } else {
      $verified = password_verify($password, $user[0]['password']);
      if(!$verified) {
        $error["email"] = "Email atau password salah!";
      } else {
        unset($user[0]["password"]);
        $_SESSION['user'] = $user[0];
        header("Location: /index.php");
        return;
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
  </head>
  <body>
    <div class="form-card-container">
      <div class="form-card">
        <h1>Login</h1>
        <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
          <div class="form-control">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required value="<?= post_value('email') ?>" />
            <?php error_message($error, 'email') ?>
          </div>
          <div class="form-control">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required />
            <?php error_message($error, 'password') ?>
          </div>
          <div class="form-control">
            <button type="submit">Login</button>
          </div>
        </form>
      </div>
    </div> 
  </body>
</html>
