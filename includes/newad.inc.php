<?php
session_start();
require 'dbh.inc.php';

//$target_dir = "uploads/";
//$target_file = $target_dir.basename($_FILES["fileToUpload"]["name"]);
//$uploadOk = 1;
//$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["ad-submit"])) {
//    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
//    if($check !== false) {
//        echo "Bestand is een foto - " . $check["mime"] . ".";
//        $uploadOk = 1;
//    } else {
//        echo "Dit bestand is geen foto.";
//        $uploadOk = 0;
//    }

    $plantname = $_POST["pname"];
    $plantlatinname = $_POST["psoort"];
    $plantcategory = $_POST["type"];
    $desc = $_POST["desc"];
    $water = $_POST["water"];
    $light = $_POST["licht"];
    $userId = $_SESSION['userId'];

    if(empty($plantlatinname)) {
        $plantlatinname = "weet ik niet";
    }

    if (empty($plantname) || empty($plantcategory) || empty($water) || empty($light) || empty($desc)) {
        header("Location: ../newad.php?error=emptyfields");
        exit();
    }
    else {
        $sql = "INSERT INTO Advertisement(plantName, plantLatinName, plantCategory, plantDesc, waterManage, lightPattern, Userid) VALUES(?,?,?,?,?,?,?)";
        $statement = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($statement, $sql)) {
            header("Location: ../newad.php?error=sqlerror");
            exit();
        }
        else {
            mysqli_stmt_bind_param($statement, "ssssiii", $plantname, $plantlatinname, $plantcategory, $desc, $water, $light, $userId);
            mysqli_stmt_execute($statement);
            echo "done!";

        }
    }
}
/*
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, deze foto bestaat al.";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 1000000) {
    echo "Sorry, uw foto is te groot.";
    $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
    echo "Sorry, alleen JPG, JPEG, PNG & GIF bestanden worden ondersteund.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, er is iets mis gegaan, probeer het opnieuw.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "Uw bestand: ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " is geupload!.";
    } else {
        echo "Sorry, er heeft zich een probleem voorgedaan tijdens het uploaden.";
    }
}
*/