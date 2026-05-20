<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "rh_system";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión");
}

?>