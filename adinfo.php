<?php
    include('header.php');
?>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.3/tiny-slider.css">
</head>

<?php
    require 'includes/dbh.inc.php';

    $id = $_GET['idAd'];

    $sql = "SELECT * FROM Advertisement a JOIN User u ON a.userId = u.idUser JOIN AdImage ai ON a.idAd = ai.idAdvert WHERE a.idAd = '$id'";
    $result = mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
        // output data of each row

        $row = $result->fetch_assoc();
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

                                for($i=0; $i<=5; $i++) {

                                    if ($light >= 1) {
                                        echo '<span class="fas fa-lightbulb light-checked"></span>';
                                        $light--;
                                    } else {
                                        echo '<span class="fas fa-lightbulb"></span>';
                                        }
                                }
                                echo '
                                <h3 class="plantInner">Water:</h3>';

                                $light = $row["waterManage"]*2;

                                for($i=0; $i<=5; $i++) {

                                    if ($light >= 1) {
                                        echo '<span class="fas fa-tint drop-checked"></span>';
                                        $light--;
                                    } else {
                                        echo '<span class="fas fa-tint"></span>';
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
                                <h3 class="plantInner">Geupload door:</h3>
                                <p class="plantInner">'.$row["usernameUser"].'</p>
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
                        <div class="moreAdsImg">
                            <img src="uploads/'.$row["imgName"].'" alt="">
                            <img src="uploads/'.$row["imgName"].'" alt="">
                            <img src="uploads/'.$row["imgName"].'" alt="">
                        </div>
                    </div>
                </div>';
    } else {
        echo "0 results";
    }
    $conn->close();

include('footer.php');
include('feedback.php');
?>