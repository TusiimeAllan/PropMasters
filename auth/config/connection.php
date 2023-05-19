<?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'prop_masters';

$conn = mysqli_connect($host, $username, $password, $database);

if ($conn) {
    // echo "Database connected";
} else {
    // echo "Not connected";
}
