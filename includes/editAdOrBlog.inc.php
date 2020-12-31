<?php
    require 'dbh.inc.php';

    if($_POST['function'] == "blogpost"){
        $imgName = $_POST['blogpostImage'];

        $path = '../uploads/'. $imgName;
        if(unlink($path)){
            $sql = "DELETE FROM BlogImage WHERE imgName = '$imgName'";
            if ($conn->query($sql) === TRUE) {
                echo "success";
            }
        }
    }
?>