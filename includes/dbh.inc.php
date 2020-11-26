<?php

$servername = "localhost";
$dBUsername = "royvan1q_user_dekas";
$dBPassword = "Bossex123!";
$dBName = "royvan1q_websitedekas";

$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

if (!$conn) {
    die("Connection failed: ".mysqli_connect_error());
}