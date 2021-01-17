<?php
    session_start();
    include('header.php');
?>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.3/tiny-slider.css">
    <link rel="stylesheet" type="text/css" href="css\BlogStyle.css">
    <link rel="stylesheet" type="text/css" href="css\NewAdStyle.css">
</head>

<body>
    <div id="userDisplayAdvertisement">
        <?php
            require 'includes/dbh.inc.php';
            include 'distance.php';

            $id = $_GET['idAd'];

            $sql = "SELECT * FROM Advertisement a JOIN User u ON a.userId = u.idUser LEFT JOIN AdImage ai ON a.idAd = ai.idAdvert WHERE a.idAd = '$id' AND a.AdStatus = '1'";
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
                                    <div class="plantInfoMargin">';
                                        if(isset($_SESSION['userId'])){
                                            if($_SESSION['userId'] != $row['userId']){
                                                echo'
                                                    <a class="plantInner" href="mailto:'.$row["emailUser"].'?subject=Fleurt op '.$row["plantName"].' advertentie">
                                                    <button class="plantMsg">Bericht sturen</button>
                                                    </a>';
                                            }
                                        }

                                    echo '
                                        <h3 class="plantInner">Licht:</h3>';

                                        $light = $row["lightPattern"];
                                        if ($light == 0) {
                                            echo '<span class="fas fa-question"></span>';
                                        } else {
                                            for ($i = 0; $i <= 2; $i++) {

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

                                        $water = $row["waterManage"];
                                        if ($water == 0) {
                                            echo '<span class="fas fa-question"></span>';
                                        } else {
                                            for($i=0; $i<=2; $i++) {

                                                if ($water >= 1) {
                                                    echo '<span class="fas fa-tint drop-checked">&nbsp;</span>';
                                                    $water--;
                                                } else {
                                                    echo '<span class="fas fa-tint">&nbsp;</span>';
                                                    }
                                            }
                                        }
                                        echo '
                                        <h3 class="plantInner">Soort:</h3>
                                        <p class="plantInner">'.$row["plantCategory"].'</p>
                                        <h3 class="plantInner">Datum:</h3>
                                        <p class="plantInner">'.date_format(date_create($row["postDate"]),"d-m-Y").'</p>';

                                        if (isset($_SESSION['userId'])) {
                                            // Retrieve postal code from current user
                                            $currentUserId = $_SESSION['userId'];
                                            $sqlForPostalCode = "SELECT postalCode FROM User WHERE idUser = $currentUserId";
                                            $resultSql = $conn->query($sqlForPostalCode);

                                            if ($resultPostalCode = mysqli_fetch_assoc($resultSql)) {
                                                $currentUserPostalCode = $resultPostalCode['postalCode'];
                                            }

                                            $distance = getDistance($row['postalCode'], $currentUserPostalCode);
                                        } else {
                                            $distance = "-- km";
                                        }

                                        echo '
                                        <h3 class="plantInner">Afstand:</h3>
                                        <p class="plantInner">'.$distance.'</p>
                                        <h3 class="plantInner">Beoordeling:</h3>';
                                            $sql = "SELECT * FROM Rating WHERE advertisementId = '$id'";
                                            $result = $conn->query($sql);

                                            $allPlantQualityRates = array();
                                            foreach($result as $ratingId) {
                                                // output data of each row
                                                array_push($allPlantQualityRates, $ratingId['plantQuality']);
                                            }
                                            $averagePlantQuality = array_sum($allPlantQualityRates) / count($allPlantQualityRates);
                                            if(count($allPlantQualityRates) == 0){
                                                $averagePlantQuality = 0;
                                            }
                                            for($i = 0; $i < $averagePlantQuality; $i++){
                                                echo '<label class="fa fa-star star-checked"></label>';
                                            }
                                            for($i = 4; $i >= $averagePlantQuality; $i--){
                                                echo '<label class="fa fa-star"></label>';
                                            }

                                            echo "<h3 style='display: inline;'>(".count($allPlantQualityRates).")</h3>";

                                            echo '
                                        <br><br>
                                        <button onclick="showRatingForm()" class="plantMsg">Beoordeling plaatsen</button>
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
                                    $sql = "SELECT ai.imgName, a.idAd, a.plantName, a.postDate FROM Advertisement a JOIN User u ON a.userId = u.idUser LEFT JOIN AdImage ai ON a.idAd = ai.idAdvert WHERE u.usernameUser = '$username' AND a.idAd != '$id' AND a.AdStatus = '1' ORDER BY a.postDate DESC";
                                    $resultInner = $conn->query($sql);
                                    $allAdvertisementIds = array(); //array to avoid printing multiple images of one advertisement

                                    if ($resultInner->num_rows > 0) {
                                        $moreAdsLimit = 3; //show only 3 other ads of user
                                        while ($row3 = mysqli_fetch_array($resultInner)) {
                                            if($row3['imgName'] != "" && !(in_array($row3['idAd'], $allAdvertisementIds))){
                                                if (isset($_SESSION['userId'])) {
                                                    $distance = getDistance($row['postalCode'], $currentUserPostalCode);
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
                        </div>
                        <!-- pop up message when rating is successfully posted -->
                        <div id="ratingSuccess">
                            <div class="blurBackground-success"></div>

                            <div class="userRatingForm">
                                <br>
                                <h1>Beoordelings<span>-</span>formulier</h1>
                                <p class="userRatingDescription">Bedankt voor uw beoordeling!</p>
                                <button class="closeRatingErrorMessage" onclick=userPopUpMessage("ratingsuccess")>Sluiten</button>
                            </div>
                        </div>
                        <!-- pop up message when user clicks on rating button -->
                        <div id="ratingForm">
                            <div class="blurBackground-success"></div>

                            <div class="userRatingForm">
                                <br>
                                <h1>Beoordelings<span>-</span>formulier</h1>';
                                    if(isset($_SESSION['userId']) && $_SESSION['userId'] != $row['idUser']){
                                        $sql = "SELECT userId FROM Rating WHERE advertisementId = '$id'";
                                        
                                        $result = $conn->query($sql);
                                        $ratingUserIds = array();
                                        foreach($result as $ratingId) {
                                            // output data of each row
                                            array_push($ratingUserIds, $ratingId['userId']);
                                        }
                                        if(!in_array($_SESSION['userId'], $ratingUserIds)){
                                    echo '
                                        <div class="popup-form">
                                            <p class="userRatingDescription">Vul de onderstaande gegevens in om een beoordeling te plaatsen.</p>
                                            <table class="ratingFormBody">
                                                <tr>
                                                    <td class="ratingFormBody-left">
                                                        <h2>Plantkwaliteit</h2>
                                                        <p>Wat vindt u van de kwaliteit van de plant?</p>
                                                        <div class="plant-star-container">
                                                            <div class="plant-star-widget">
                                                                <input type="radio" name="plantRating" id="plant-rate-5">
                                                                <label for="plant-rate-5" class="fa fa-star"></label>
                                                                <input type="radio" name="plantRating" id="plant-rate-4">
                                                                <label for="plant-rate-4" class="fa fa-star"></label>
                                                                <input type="radio" name="plantRating" id="plant-rate-3">
                                                                <label for="plant-rate-3" class="fa fa-star"></label>
                                                                <input type="radio" name="plantRating" id="plant-rate-2">
                                                                <label for="plant-rate-2" class="fa fa-star"></label>
                                                                <input type="radio" name="plantRating" id="plant-rate-1">
                                                                <label for="plant-rate-1" class="fa fa-star"></label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="ratingFormBody-right">
                                                        <h2>Communicatie</h2>
                                                        <p>Wat vindt u van de communicatie met de aanbieder?</p>
                                                        <div class="communication-star-container">
                                                            <div class="communication-star-widget">
                                                                <input type="radio" name="communicationRating" id="communication-rate-5">
                                                                <label for="communication-rate-5" class="fa fa-star"></label>
                                                                <input type="radio" name="communicationRating" id="communication-rate-4">
                                                                <label for="communication-rate-4" class="fa fa-star"></label>
                                                                <input type="radio" name="communicationRating" id="communication-rate-3">
                                                                <label for="communication-rate-3" class="fa fa-star"></label>
                                                                <input type="radio" name="communicationRating" id="communication-rate-2">
                                                                <label for="communication-rate-2" class="fa fa-star"></label>
                                                                <input type="radio" name="communicationRating" id="communication-rate-1">
                                                                <label for="communication-rate-1" class="fa fa-star"></label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                            <!-- Close popup form button -->
                                            <br>
                                            <p id="selectAllStarsWarning" class="userRatingWarning">U heeft nog niet alles beoordeeld!</p>
                                            <button class="feedback-submit" id="addRating" data-adUser='.$row['idUser'].' value='.$row["idAd"].' onclick="ratingForm(this, this.value)">Beoordeling plaatsen</button>
                                            <button class="closefeedback-submit" onclick=userPopUpMessage("rating")>Annuleren</button>
                                            <br><br>
                                        </div>';
                                        } else {
                                            $idOfUser = $_SESSION['userId'];
                                            $sql = "SELECT * FROM Rating WHERE advertisementId = '$id'";
                                        
                                            $result = $conn->query($sql);

                                            $ratingId = $result->fetch_assoc();
                                            // output data of each row
                                            $givenPlantQuality = $ratingId['plantQuality'];
                                            $givenUserRating = $ratingId['userRating'];

                                            echo '<p class="userRatingDescription">U heeft deze advertentie al beoordeeld. Hieronder kunt u uw beoordeling terugzien.</p>
                                                    <table class="ratingFormBody">
                                                        <tr>
                                                            <td class="ratingFormBody-left">
                                                                <h2>Plantkwaliteit</h2>
                                                                <p>Wat vindt u van de kwaliteit van de plant?</p>
                                                                <div class="plant-star-container">
                                                                    <div class="plant-star-widget">';                             
                                                                        for($i = 0; $i < $givenPlantQuality; $i++){
                                                                            echo '<label for="plant-rate-1" class="fa fa-star star-checked"></label>';
                                                                        }
                                                                    echo '
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="ratingFormBody-right">
                                                                <h2>Communicatie</h2>
                                                                <p>Wat vindt u van de communicatie met de aanbieder?</p>
                                                                <div class="communication-star-container">
                                                                    <div class="communication-star-widget">';                             
                                                                    for($i = 0; $i < $givenUserRating; $i++){
                                                                        echo '<label for="communication-rate-1" class="fa fa-star star-checked"></label>';
                                                                    }
                                                                echo '
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <br>
                                                  <button class="closeRatingErrorMessage" onclick=userPopUpMessage("rating")>Sluiten</button>';
                                        }
                                    } else if($_SESSION['userId'] == $row['idUser']){
                                        echo "<p class='userRatingDescription'>U kunt niet uw eigen advertentie beoordelen</p>
                                              <button class='closeRatingErrorMessage' onclick=userPopUpMessage('rating')>Sluiten</button>";
                                        
                                    } else {
                                        echo "<p class='userRatingDescription'>Om een advertentie te kunnen beoordelen moet u eerst ingelogd zijn. Klik <a href='loginpagina'>HIER</a> om in te loggen.</p>
                                              <button class='closeRatingErrorMessage' onclick=userPopUpMessage('rating')>Sluiten</button>";
                                    }
                            echo '
                            </div>
                        </div>';
                // session used for edit page to check if user is the owner of the advertisement        
                $_SESSION["idUser"] = $row["idUser"];
            //Give error when advertisement doesn't exist
            } else {
                echo "<div class='newaderror'><p>Advertentie bestaat niet meer.</p></div>";
            }
            $conn->close();
        ?>
    </div>
</body>

<?php
    include('footer.php');
    include('feedback.php');
?>

<script>
    //advertisementId is the id of the advertisement stored in the button value
    function userDeleteAdvertisement(advertisementId, advertisementUser){
        $.ajax({
            url: "adminFunctions.php",
            type: 'post',
            data: {function: "advertisement", id: advertisementId, user: advertisementUser},
            success: function(result)
            {
                //display result after clicking on "delete advertisement"
                document.getElementById("userDisplayAdvertisement").innerHTML = result;
                document.getElementById("userDisplayAdvertisement").style.cssText = "height: 75vh;";
            }
        })
    }

    function showRatingForm(){
        document.getElementById("ratingForm").style.cssText = "display: block;";
    }

    function showDeletePopUp(){
        document.getElementById("userDeleteBlogpostPopUp").style.cssText = "display: block;";
    }

    function userPopUpMessage(chosenOption){
        document.getElementById("userDeleteBlogpostPopUp").style.cssText = "display: none;";
        //reset rating form when user clicks cancel
        if(chosenOption == "rating"){
            document.getElementById("ratingForm").style.cssText = "display: none;";
            var plantRating = document.getElementsByName('plantRating');
            var communicationRating = document.getElementsByName('communicationRating');
            for (var i = 0, length = plantRating.length; i < length; i++) {
                plantRating[i].checked = false;
                communicationRating[i].checked = false;
            }
        } else if(chosenOption == "ratingsuccess"){
            document.getElementById("ratingSuccess").style.cssText = "display: none;";
            location.reload();
        }
    }

    function ratingForm(ratingFormSelf, adId){
        var plantRating = document.getElementsByName('plantRating');
        var communicationRating = document.getElementsByName('communicationRating');
        var adUser = $(ratingFormSelf).attr('data-adUser');
        var ischecked_method = false;
        var ischecked_method2 = false;
        var plantRatingValue;
        var communicationRatingValue;

        //checks if user has filled in all required fields in rating form
        for (var i = 0, length = plantRating.length; i < length; i++) {
            if (plantRating[i].checked) {
                ischecked_method = true;
                plantRatingValue = plantRating[i].id.slice(-1);
            }
            if(communicationRating[i].checked){
                ischecked_method2 = true;
                communicationRatingValue = communicationRating[i].id.slice(-1);
            }
        }
        
        if(!ischecked_method || !ischecked_method2)   {
            document.getElementById("selectAllStarsWarning").style.cssText = "display: block;";
        } else { //if user filled in all required fields in rating form
            document.getElementById("selectAllStarsWarning").style.cssText = "display: none;";

            //add rating to table
            $.ajax({
                url: "includes/rating.inc.php",
                type: 'post',
                data: {advertisementId: adId, plantQuality: plantRatingValue, userRating: communicationRatingValue, advertisementUser: adUser},
                success: function(result)
                {
                    //checks which list needs to be updated
                    if(result == "success"){
                        document.getElementById("ratingForm").style.cssText = "display: none;";
                        userPopUpMessage("rating"); //remove chosen stars
                        document.getElementById("ratingSuccess").style.cssText = "display: block;";
                    }
                }
            })
        }
    }
</script>