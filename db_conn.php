<?php
$sname = "localhost";
$uname = "root";
$password = "";
$db_name = "iom";
$conn = mysqli_connect($sname, $uname, $password, $db_name);
 mysqli_set_charset($conn,"utf8"); 

if (!$conn) {echo "Connection Failed!";exit();}