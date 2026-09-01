<?php
$db_server = "localhost";
$db_user   = "root";
$db_pass   = "";
$db_name   = "iom";

$link = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
mysqli_set_charset($link,"utf8"); 

if (!$link) {
  die("Connection failed: " . mysqli_connect_error());
}
?>
