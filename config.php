<?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$database_name = "gym";

$conn = mysqli_connect($servername, $db_username, $db_password, $database_name);


session_start();

if (!$conn) {
    die("conncetion not exist");
}
