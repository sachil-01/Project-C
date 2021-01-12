<?php
    session_start();
    require 'includes/dbh.inc.php';
    require 'distance.php';

    if (isset($_SESSION['userId'])) {
        // Retrieve postal code from current user
        $currentUserId = $_SESSION['userId'];
        $sql = "SELECT postalCode FROM User WHERE idUser = $currentUserId";
        $statement = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($statement, $sql)) {
            header("Location: adpagina.php?error=sqlerror");
            echo '<div class="newposterror"><p>Er is iets fout gegaan met het ophalen van de postcode(sql error).</p></div>';
        }
        else {
            mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);
            if ($row = mysqli_fetch_assoc($result)) {
                $currentUserPostalCode = $row['postalCode'];
            }
        }
    }

    $searchInput = $_POST['searchInput'];
    $plantCategories = $_POST['filters'];
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];
    $selectedDistance = $_POST['selectedDistance'];

    //search function
        if(!empty($searchInput)){
            //split search input by every space
            $searchpieces = explode(" ", $searchInput);
            //for loop to create "a.plantName LIKE '%$array[0]%'" for every array item
            $plantNameLikeString = "a.plantName LIKE ";    
            for ($i = 0; $i < count($searchpieces); $i++){
                if($i == count($searchpieces) - 1){
                    $plantNameLikeString = $plantNameLikeString . "'%".$searchpieces[$i]."%'";
                } else {
                    $plantNameLikeString = $plantNameLikeString . "'%".$searchpieces[$i]."%' OR a.plantName LIKE ";
                }
            }
            $usernameUserLikeLikeString = "u.usernameUser LIKE ";    
            for ($i = 0; $i < count($searchpieces); $i++){
                if($i == count($searchpieces) - 1){
                    $usernameUserLikeLikeString = $usernameUserLikeLikeString . "'%".$searchpieces[$i]."%'";
                } else {
                    $usernameUserLikeLikeString = $usernameUserLikeLikeString . "'%".$searchpieces[$i]."%' OR u.usernameUser LIKE ";
                }
            }
            $sql = "SELECT DISTINCT * FROM Advertisement a JOIN User u ON a.userId = u.idUser JOIN AdImage ai ON a.idAd = ai.idAdvert WHERE ( $plantNameLikeString OR $usernameUserLikeLikeString )";
        } else {
            $sql = "SELECT * FROM Advertisement a JOIN User u ON a.userId = u.idUser JOIN AdImage ai ON a.idAd = ai.idAdvert";
        }


        //filter options
        if(!empty($plantCategories)){
            $plantCategories = "('" . implode("','", $plantCategories) . "')";

            $sql2[] = "( a.plantCategory IN $plantCategories )";
        }

        if (!empty($fromDate)) {
            $sql2[] = "( a.postDate >= '$fromDate' )";
        }
        if (!empty($toDate)) {
            $sql2[] = "( a.postDate < '$toDate' OR a.postDate LIKE '%$toDate%' )";
        }

        if (!empty($sql2)) {
            if(!empty($searchInput)){
                $sql .= ' AND ' . implode(' AND ', $sql2);
            } else {
                $sql .= ' WHERE ' . implode(' AND ', $sql2);
            }
        }
        //DATUM QUERY RESET QUERY!!!
        $sql .= ' ORDER BY a.idAd DESC ';

        $statement = mysqli_stmt_init($conn);
        //array with all advertisement Ids
        $allIdAdvertisements = array();
        $allAdvertisementsHTML;
        if (!mysqli_stmt_prepare($statement, $sql)) {
            header("Location: adpagina.php?error=sqlerror");
            echo '<div class="newposterror"><p>Er is iets fout gegaan tijdens het uitvoeren van de sql query(sql error).</p></div>';
        }
        else {
            mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    //checks if advertisement id already exists in array > if advertisement id exists in array -> skip current advertisement
                    if(!in_array($row['idAd'], $allIdAdvertisements)){
                        if (isset($_SESSION['userId'])) {
                            $distance = getDistance($row['postalCode'], $currentUserPostalCode);
                            $getNumberFromDistance = explode(" ", $distance);
                            if( $getNumberFromDistance[0] <= $selectedDistance){
                                $allowDisplay = true;
                            } else {
                                $allowDisplay = false;
                            }
                        } else {
                            $distance = "-- km";
                            $allowDisplay = true;
                        }
                        if( $allowDisplay ){
                            $allAdvertisementsHTML = $allAdvertisementsHTML . '<div class="plant">
                                                                                    <a class="linkPlant" href="adinfo?idAd='.$row['idAd'].'">
                                                                                        <div class="adImage">
                                                                                            <img src="uploads/'.$row["imgName"].'" alt="">
                                                                                        </div>
                                                                                        <div class="description">
                                                                                            <h2>'.$row['plantName'].'</h2>
                                                                                            <br>
                                                                                            <h3> Afstand: <span>'.$distance.'</span></h3>
                                                                                            <h3> Datum: <span>'.date("d-m-Y", strtotime($row['postDate'])).'</span></h3>
                                                                                        </div>
                                                                                    </a>
                                                                                </div>';
                            //add advertisement id to array
                            array_push($allIdAdvertisements, $row['idAd']);
                        }
                    }
                }
                echo "$allAdvertisementsHTML";
            } else {
                echo "0 resultaten";
            }
        }
?>