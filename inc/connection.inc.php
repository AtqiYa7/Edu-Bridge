<?php

// Set default timezone
date_default_timezone_set("Asia/Dhaka");

$con = new mysqli('localhost', 'root', '', 'main_db');

if($con->connect_errno > 0){
    die('Unable to connect to database [' . $con->connect_error . ']');
}

// Set MySQL timezone to match PHP timezone
$con->query("SET time_zone = '+06:00'");

?>