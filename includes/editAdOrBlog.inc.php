<?php
    require 'dbh.inc.php';

    $imgName = $_POST['blogpostImage'];
    $path = '../uploads/'. $imgName;

    if($_POST['function'] == "blogpost"){
        if(unlink($path)){
            $sql = "DELETE FROM BlogImage WHERE imgName = '$imgName'";
            if ($conn->query($sql) === TRUE) {
                echo "success";
            }
        }
    } else {
        if(unlink($path)){
            $sql = "DELETE FROM AdImage WHERE imgName = '$imgName'";
            if ($conn->query($sql) === TRUE) {
                echo "success";
            }
        }
    }
?>