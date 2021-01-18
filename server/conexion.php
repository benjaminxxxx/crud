<?php 

error_reporting(0);

$mysqli = new mysqli("localhost", "root", "", "crud");

$data = [];

if (mysqli_connect_errno()) {
    $data['message'] = "Error: " . $mysqli->connect_error;
    $data['response'] = false;
    die(json_encode($data));
}

