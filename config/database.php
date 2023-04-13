<?php
define('DB_HOST', '220142483.cs2410-web01pvm.aston.ac.uk');
define('DB_USER', 'u-220142483');
define('DB_PASS', 'wBKmOiiM1vKvx4K');
define('DB_NAME', 'u_220142483_db');

//Create connnection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

//Check connection
if ($conn->connect_error) {
    die('Connection Failed ' . $conn->connect_error);
}
