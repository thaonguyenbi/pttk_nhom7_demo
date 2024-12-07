<?php
$server = 'localhost';
$user = 'root';
$pass = '';
$database = 'maycha';
$port = '3306';

$conn = mysqli_connect($server, $user, $pass, $database, $port);

if ($conn) {
    mysqLi_query($conn, "SET NAMES 'utf8' ");
} else {
    echo "Fail";
}