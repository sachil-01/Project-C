<?php
    session_start();
    require 'dbh.inc.php';
    $plantQuality = $_POST['plantQuality'];
    $userRating = $_POST['userRating'];
    $advertisementId = $_POST['advertisementId'];
    $adUser = $_POST['advertisementUser'];
    //avoid users changing rating
    if($userRating > 5){
        $userRating = 5;
    }
    if($plantQuality > 5){
        $plantQuality = 5;
    }
    // Insert rating data into Rating table
    $sql = "INSERT INTO Rating(plantQuality, userRating, advertisementId, adUser) VALUES ('$plantQuality', '$userRating', '$advertisementId', '$adUser')";
    if($conn->query($sql)){
        echo "success";
    }
?>