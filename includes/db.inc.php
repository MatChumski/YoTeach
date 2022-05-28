<?php

$server = "localhost";
$dbUsername = "root";
$dbPassword = "02040521";
$dbName = "yoteach";

$conn = mysqli_connect($server, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
