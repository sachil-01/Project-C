<?php
    require 'includes/dbh.inc.php';
                                                                                                     //EXAMPLE: DATE_ADD(2021-1-3 + 2 months) = 2021-1-3
    $sql = "SELECT idAd FROM Advertisement WHERE ((plantCategory = 'stekje' OR plantCategory = 'kiemplant') AND DATE_ADD(date_format(postDate, '%Y-%m-%d'), INTERVAL 2 MONTH) = CURRENT_DATE)
            OR (plantCategory = 'zaad' AND  DATE_ADD(date_format(postDate, '%Y-%m-%d'), INTERVAL 1 YEAR) = CURRENT_TIMESTAMP)";

    //collect all (expired) advertisement id's
    $allAdvertisementId = array();
    $result = $conn->query($sql);                                  //make connection with database and run query
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            array_push($allAdvertisementId, $row["idAd"]);
        }
    }
                                                                                                //EXAMPLE: DATE_ADD(2021-1-3 + 2 months) = 2021-1-3
    $sql = "DELETE FROM Advertisement WHERE ((plantCategory = 'stekje' OR plantCategory = 'kiemplant') AND DATE_ADD(date_format(postDate, '%Y-%m-%d'), INTERVAL 2 MONTH) = CURRENT_DATE)
            OR (plantCategory = 'zaad' AND  DATE_ADD(date_format(postDate, '%Y-%m-%d'), INTERVAL 1 YEAR) = CURRENT_TIMESTAMP)";
    
    //delete expired advertisements
    $conn->query($sql);

    //loop through each advertisement
    foreach($allAdvertisementId as $advertisementId){
        //collect all image names from specific advertisement
        $sql = "SELECT imgName FROM AdImage WHERE idAd = '$advertisementId'";
        $result = $conn->query($sql);                                    //make connection with database and run query
        $imgNames = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($imgNames, $row['imgName']);
            }
        }

        //delete all advertisement pictures (in array) from uploads folder
        foreach($imgNames as $imgName){
            $path = 'uploads/'.$imgName;
            unlink($path);
        }
    }
?>