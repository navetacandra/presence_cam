<?php
session_start();
$conn = new mysqli("localhost", "root", "root", "presence_cam");
if($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

function is_authenticated(): bool {
  return isset($_SESSION['user']);
}

function is_admin(): bool {
  return is_authenticated() && $_SESSION['user']['is_admin'];
}

function error_message($error, $key) {
  if(isset($error[$key])) {
?>
<small style="color: red;"><?=$error[$key] ?></small>
<?php
  }
}

function post_value_initial($name, $arr): string {
  if(isset($_POST[$name])) return $_POST[$name];
  if(isset($arr[$name])) return $arr[$name];
  return "";
}

function post_value($name): string {
  if(isset($_POST[$name])) return $_POST[$name];
  return "";
}

function query_all($conn, $sql, $type="", ...$args) {
  $stmt = $conn->prepare($sql);
  if(!empty($type)) {
    $stmt->bind_param($type, ...$args);
  }
  $stmt->execute();
  return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function query_execute($conn, $sql, $type, ...$args): bool {
  $stmt = $conn->prepare($sql);
  $stmt->bind_param($type, ...$args);
  $stmt->execute();
  return $stmt->affected_rows > 0;
}
