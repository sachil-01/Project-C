<?php
    session_start();
    include('header.php');
?>
<script src="adpagina.js"></script>

<head>
    <title>Advertisements</title>
    <link rel="stylesheet" type="text/css" href="css\adPagina.css">
</head>

<body>    
    <div class="gallery">
        <h1>Alle aanbiedingen</h1>
        <a class="newadknop" href="newad"><i class="fas fa-plus"></i>Plant plaatsen</a>

        
        <div class="searchbar-div">
            <div class="searchbar-margin">
                <div class="searchbar-main">
                    <div class="searchbar-main-content">
                        <form action="" method="post">
                            <input type="text" class="searchbar-input" name="search-input" onfocus="this.value=''" placeholder="Zoeken...">
                            <button class="" name="search-submit">Zoeken</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div> 

    <div class="img-area">
        <?php 
        require 'includes/dbh.inc.php';
        include 'distance.php';

        if (isset($_SESSION['userId'])) {
            // Retrieve postal code from current user
            $currentUserId = $_SESSION['userId'];
            $sql = "SELECT postalCode FROM User WHERE idUser = $currentUserId";
            $statement = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($statement, $sql)) {
                header("Location: adpagina.php?error=sqlerror");
                echo '<div class="newposterror"><p>Er is iets fout gegaan (sql error).</p></div>';
            }
            else {
                mysqli_stmt_execute($statement);
                $result = mysqli_stmt_get_result($statement);
                if ($row = mysqli_fetch_assoc($result)) {
                    $currentUserPostalCode = $row['postalCode'];
                }
            }
        }

        if(isset($_POST['search-submit'])){
            $searchvalue = $_POST['search-input'];
            //split search input by every space
            $searchpieces = explode(" ", $searchvalue);
            //for loop to create "a.plantName LIKE '%$array[0]%'" for every array item
            $temp = "a.plantName LIKE ";    
            for ($i = 0; $i < count($searchpieces); $i++){
                if($i == count($searchpieces) - 1){
                    $temp = $temp . "'%".$searchpieces[$i]."%'";
                } else {
                    $temp = $temp . "'%".$searchpieces[$i]."%' OR a.plantName LIKE ";
                }
            }
            $sql = "SELECT DISTINCT * FROM Advertisement a JOIN User u ON a.userId = u.idUser JOIN AdImage ai ON a.idAd = ai.idAdvert WHERE $temp ORDER BY a.idAd DESC";
        } else {
            $sql = "SELECT * FROM Advertisement a JOIN User u ON a.userId = u.idUser JOIN AdImage ai ON a.idAd = ai.idAdvert ORDER BY a.idAd DESC";
        }
        
        $statement = mysqli_stmt_init($conn);
        //array with all blogpost Ids
        $allIdAdvertisements = array();
        if (!mysqli_stmt_prepare($statement, $sql)) {
            header("Location: adpagina.php?error=sqlerror");
            echo '<div class="newposterror"><p>Er is iets fout gegaan (sql error).</p></div>';
        }
        else {
            mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);
            if ($row = mysqli_fetch_assoc($result)) {
                foreach ($result as $adv) {
                    //checks if advertisement id already exists in array > if advertisement id exists in array -> skip current advertisement
                    if(!in_array($adv['idAd'], $allIdAdvertisements)){
                        $idAd = $adv['idAd'];
                        $plantName = $adv['plantName'];
                        $plantLatinName = $adv['plantLatinName'];
                        $adDate = date("d-m-Y", strtotime($adv['postDate']));

                        if (isset($_SESSION['userId'])) {
                            $distance = getDistance($adv['postalCode'], $currentUserPostalCode);
                        } else {
                            $distance = "-- km";
                        }
                        echo '<div class="plant">
                            <div class="adImage">
                                <a href="adinfo?idAd='.$idAd.'"><img src="uploads/'.$adv["imgName"].'" alt=""></a>
                            </div>
                            <div class="description">
                                <h2>'.$plantName.'</h2>
                                <br>
                                <h3> Afstand: <span>'.$distance.'</span></h3>
                                <h3> Datum: <span>'.$adDate.'</span></h3>
                            </div>
                        </div>';
                        //add advertisement id to array
                        array_push($allIdAdvertisements, $adv['idAd']);
                    }
                }
            }
        };
        ?>
    </div>
</body>

<?php
    include('footer.php');
    include('feedback.php');
?>

