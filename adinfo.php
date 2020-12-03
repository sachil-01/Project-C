<?php
    include('header.php');
?>

<head>
    <link rel="stylesheet" type="text/css" href="css/adinfo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.3/tiny-slider.css">
</head>

<?php

            $servername = "localhost";
            $dBUsername = "root";
            $dBPassword = "root";
            $dBName = "royvan1q_websitedekas";

            $conn = new mysqli($servername, $dBUsername, $dBPassword, $dBName);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM Advertisement a JOIN User u ON a.userId = u.idUser JOIN AdImage ai ON a.idAd = ai.idImage ORDER BY a.idAd DESC";
            $result = $conn->query($sql);
            $number_of_posts = $result->num_rows;
            if ($result->num_rows > 0) {
                // output data of each row

                while ($row = $result->fetch_assoc()) {
                    echo '<div class="advWrapper">
                        <div class="slidertns">
                            <img src="uploads/'.$row["imgName"].'" alt="">
                            <img src="uploads/'.$row["imgName"].'" alt="">
                            <img src="uploads/'.$row["imgName"].'" alt="">
                        </div>
                        <div class="blogDescription">
                            <h2>'.$row["plantName"].'</h2>
                            <h3>'.$row["plantLatinName"].'</h3>
                            <h3>Beschrijving</h3>
                            <p>'.$row["plantDesc"].'</p>
                            <p>'.$row["waterManage"].'</p>
                            <h4 class="alignleft">'.date_format(date_create($row["postDate"]),"d-m-Y").'</h4>
                            <h4 class="alignright">'.$row["plantCategory"].'</h4>
                        </div>
                    </div>';
                }

            } else {
                echo "0 results";
            }
            $conn->close();
            ?>

<!-- <body>
    <div class="adwrapper">
        <div class="slidertns">
            <img src="images/plant1.jpg" alt="">
            <img src="images/plant2.jpg" alt="">
        </div>
        <div class="advTitle">
            <h1>Test</h1>
        </div>
    </div>
</body> -->

<?php 
    include('footer.php')
?>