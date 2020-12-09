<?php
    include('header.php');
?>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.3/tiny-slider.css">
</head>

<?php
    require 'includes/dbh.inc.php';
    
    $conn = new mysqli($servername, $dBUsername, $dBPassword, $dBName);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $id = $_GET['idAd'];

    $sql = "SELECT * FROM Advertisement a JOIN User u ON a.userId = u.idUser JOIN AdImage ai ON a.idAd = ai.idAdvert WHERE a.idAd = '$id'";
    $result = $conn->query($sql);
    $number_of_posts = $result->num_rows;
    if ($result->num_rows > 0) {
        // output data of each row

        while ($row = $result->fetch_assoc()) {
           echo'<div class="advWrapper">
                    <div class="slidertns">
                        <img src="uploads/'.$row["imgName"].'" alt="">
                        <img src="uploads/'.$row["imgName"].'" alt="">
                        <img src="uploads/'.$row["imgName"].'" alt="">
                    </div>
                    <div class="plantInfo">
                        <div class="plantInfoMargin">
                            <a href="mailto:'.$row["emailUser"].'?subject=Fleurt op '.$row["plantName"].' advertentie">
                            <button class="plantMsg">Bericht sturen</button>
                            </a>
                            <h3>Licht:</h3>
                            <p>'.$row["lightPattern"].'</p>
                            <h3>Water:</h3>
                            <p>'.$row["waterManage"].'</p>
                            <h3>Soort:</h3>
                            <p>'.$row["plantCategory"].'</p>
                            <h3>Datum:</h3>
                            <p>'.date_format(date_create($row["postDate"]),"d-m-Y").'</p>
                            <h3>Beoordeling:</h3>
                            <p>Later nog doen</p>
                            <h3>Geupload door:</h3>
                            <p>'.$row["usernameUser"].'</p>
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
        }

    } else {
        echo "0 results";
    }
    $conn->close();

include('footer.php')
?>