<?php
include "./connection.php";

if(is_authenticated()) {
  if(is_admin()) {
    return header('Location: /admin');
  } else {
    return header('Location: /user');
  }
} else {
  return header('Location: /auth/login.php');
}
