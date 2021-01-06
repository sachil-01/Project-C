<?php
    session_start();
    include('header.php');
?>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.3/tiny-slider.css">
    <link rel="stylesheet" type="text/css" href="css\BlogStyle.css">
</head>

<body>
    <div id="userDisplayAdvertisement">
        <?php
            require 'includes/dbh.inc.php';

            $id = $_GET['idAd'];

            $sql = "SELECT * FROM Advertisement a JOIN User u ON a.userId = u.idUser LEFT JOIN AdImage ai ON a.idAd = ai.idAdvert WHERE a.idAd = '$id'";
            $result = mysqli_query($conn, $sql);

            if ($result->num_rows > 0) {
                // output data of each row
                $row = $result->fetch_assoc();

                //checks if user is the publisher of the advertisement
                if($row["userId"] == $_SESSION['userId']){
                    echo '<div class="userFunctions-btn">
                        <button onclick="showDeletePopUp()" class="user-delete-blogpost-btn">Verwijder</button>
                        <a href="editAdOrBlog.php?advertisementId='.$row["idAd"].'"><button class="user-edit-blogpost-btn">Wijzig</button></a>
                        </div>';
                }

                echo'<div class="advWrapper">
                                <div class="slidertns">';
                                    $resultInner = $conn->query($sql);
                                        while ($row2 = mysqli_fetch_array($resultInner)) {

                                            echo ' <img src="uploads/'.$row2["imgName"].'" alt="">';

                                        }
                            echo' </div>
                                <div class="plantInfo">
                                    <div class="plantInfoMargin">
                                        <a class="plantInner" href="mailto:'.$row["emailUser"].'?subject=Fleurt op '.$row["plantName"].' advertentie">
                                        <button class="plantMsg">Bericht sturen</button>
                                        </a>
                                        <h3 class="plantInner">Licht:</h3>';

                                        $light = $row["lightPattern"] * 2;
                                        if ($light == 0) {
                                            echo '<span class="fas fa-question"></span>';
                                        } else {
                                            for ($i = 0; $i <= 5; $i++) {

                                                if ($light >= 1) {
                                                    echo '<span class="fas fa-sun sun-checked"></span>';
                                                    $light--;
                                                } else {
                                                    echo '<span class="fas fa-sun"></span>';
                                                }
                                            }
                                        }
                                        echo '
                                        <h3 class="plantInner">Water:</h3>';

                                        $light = $row["waterManage"]*2;
                                        if ($light == 0) {
                                            echo '<span class="fas fa-question"></span>';
                                        } else {
                                            for($i=0; $i<=5; $i++) {

                                                if ($light >= 1) {
                                                    echo '<span class="fas fa-tint drop-checked">&nbsp;</span>';
                                                    $light--;
                                                } else {
                                                    echo '<span class="fas fa-tint">&nbsp;</span>';
                                                    }
                                            }
                                        }
                                        echo '
                                        <h3 class="plantInner">Soort:</h3>
                                        <p class="plantInner">'.$row["plantCategory"].'</p>
                                        <h3 class="plantInner">Datum:</h3>
                                        <p class="plantInner">'.date_format(date_create($row["postDate"]),"d-m-Y").'</p>
                                        <h3 class="plantInner">Beoordeling:</h3>
                                        <span class="fa fa-star star-checked"></span>
                                        <span class="fa fa-star star-checked"></span>
                                        <span class="fa fa-star star-checked"></span>
                                        <span class="fa fa-star"></span>
                                        <span class="fa fa-star"></span>
                                        <br><br>
                                        <a class="plantInner" href=""><button class="plantMsg">Beoordeling plaatsen</button></a>
                                        <h3 class="plantInner">Geupload door:</h3>
                                        <p class="plantInner"><a href="userpage?IdUser='.$row["userId"].'">'.$row["usernameUser"].'</a></p>
                                    </div>
                                </div>

                            <div class="plantDescription">
                                <h2>'.$row["plantName"].'</h2>
                                <h3>'.$row["plantLatinName"].'</h3>
                                <h3 class="plantDesc">Beschrijving</h3>
                                <p>'.$row["plantDesc"].'</p>
                            </div>
                            <div class="moreAds">
                                <h3>Meer van '.$row["usernameUser"].'</h3>
                                <div class="moreAdsImg">';
                                    $username = $row["usernameUser"];
                                    $sql = "SELECT ai.imgName, a.idAd, a.plantName, a.postDate FROM Advertisement a JOIN User u ON a.userId = u.idUser LEFT JOIN AdImage ai ON a.idAd = ai.idAdvert WHERE u.usernameUser = '$username' AND a.idAd != '$id' ORDER BY a.postDate DESC";
                                    $resultInner = $conn->query($sql);
                                    $allAdvertisementIds = array(); //array to avoid printing multiple images of one advertisement

                                    if ($resultInner->num_rows > 0) {
                                        include 'distance.php';

                                        $moreAdsLimit = 3; //show only 3 other ads of user
                                        while ($row3 = mysqli_fetch_array($resultInner)) {
                                            if($row3['imgName'] != "" && !(in_array($row3['idAd'], $allAdvertisementIds))){
                                                if (isset($_SESSION['userId'])) {
                                                    $distance = getDistance($row['postalCode'], $row['postalCode']);
                                                } else {
                                                    $distance = "-- km";
                                                }
                                                echo '<div class="plant-small">
                                                        <a class="linkPlant" href="adinfo?idAd='.$row3['idAd'].'">
                                                            <div class="adImage">
                                                                <img src="uploads/'.$row3["imgName"].'" alt="">
                                                            </div>
                                                            <div class="description">
                                                                <h2>'.$row3['plantName'].'</h2>
                                                                <br>
                                                                <h3> Afstand: <span>'.$distance.'</span></h3>
                                                                <h3> Datum: <span>'.date("d-m-Y", strtotime($row3['postDate'])).'</span></h3>
                                                            </div>
                                                        </a>
                                                     </div>';
                                                array_push($allAdvertisementIds, $row3['idAd']);
                                                $moreAdsLimit--;
                                                if($moreAdsLimit == 0){
                                                    break;
                                                }
                                            }
                                        }
                                    } else {
                                        echo "<p class='moreAdsErrorMessage'>".$row["usernameUser"]." heeft geen andere advertenties geplaatst</p>";
                                    }
                                echo '
                                </div>
                            </div>
                        </div>
                        <!-- pop up message when user clicks on delete button -->
                        <div id="userDeleteBlogpostPopUp">
                            <div class="blurBackground-success"></div>

                            <div class="feedback-popup-success">
                                <br>
                                <h1>Weet u zeker dat u uw advertentie wilt verwijderen?</h1>
                                <div class="popup-form">
                                    <!-- Close popup form button -->
                                    <br>
                                    <button class="feedback-submit" id="advertisementDelete" value='.$row["idAd"].' onclick="userDeleteAdvertisement(this.value, this.id)">Verwijder advertentie</button>
                                    <button class="closefeedback-submit" onclick=userPopUpMessage()>Annuleren</button>
                                    <br><br>
                                </div>
                            </div>
                        </div>';
                // session used for edit page to check if user is the owner of the advertisement        
                $_SESSION["idUser"] = $row["idUser"];
            //Give error when advertisement doesn't exist
            } else {
                echo "Advertentie bestaat niet meer.";
            }
            $conn->close();

        include('footer.php');
        include('feedback.php');
        ?>
    </div>
</body>

<script>
    //blogpostId is the id of the blogpost stored in the button value
    function userDeleteAdvertisement(advertisementId, advertisementUser){
        $.ajax({
            url: "adminFunctions.php",
            type: 'post',
            data: {function: "advertisement", id: advertisementId, user: advertisementUser},
            success: function(result)
            {
                //display result after clicking on "delete blogpost"
                document.getElementById("userDisplayAdvertisement").innerHTML = result;
            }
        })
    }

    function showDeletePopUp(){
        document.getElementById("userDeleteBlogpostPopUp").style.cssText = "display: block;";
    }

    function userPopUpMessage(chosenOption){
        document.getElementById("userDeleteBlogpostPopUp").style.cssText = "display: none;";
    }
</script>