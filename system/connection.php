<?php
$server_name = "localhost";
$username = "root";
$password = "";
$db_name = "blog";
// Database Connections Initilazation to connect
try {
    $conn = mysqli_connect($server_name,$username,$password,$db_name);
    //code...
} catch (\Throwable $conn) {
    //throw $th;
    echo "<script>window.location.href='../404.php';</script>";
}
?>