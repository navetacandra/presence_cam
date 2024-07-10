<?php
include "./connection.php";

if(is_authenticated()) {
  if(is_admin()) {
    include "./admin/index.php";
  } else {
    include "./user/index.php";
  }
} else {
  include "./auth/login.php";
}
