<?php
    session_start();
    include('header.php');
?>

<head>
    <title>Advertisements</title>
    <link rel="stylesheet" type="text/css" href="css\adPagina.css">
</head>

<body>    
    <div class="gallery">
        <h1>Alle aanbiedingen</h1>
        <div class="newadknop">
            <button class="newadbutton"><a class="newadlink" href="newad"><i class="fas fa-plus"></i> Plant plaatsen</a></button>
        </div>
        
        <div class="searchbar-div">
            <div class="searchbar-margin">
                <div class="searchbar-main">
                        <form class="searchbar-main-content" action="" method="post">
                            <input type="text" class="searchbar-input" name="search-input" onfocus="this.value=''" placeholder="Zoeken...">
                            <button class="searchbar-button" name="search-submit"><i class="fas fa-search"></i></button>
                        </form>
                </div>
            </div>
        </div>

    </div> 

    
        <form class="search-filters" action="" method="post">
            <div class="filters">
                <div class="filterplantsoort">
                    <label>Soort</label>
                    <select class="selectsoort" name="soort">
                        <option value="">Alle</option>
                        <option value="Stekje">Stekje</option>
                        <option value="Zaad">Zaad</option>
                        <option value="Kiemplant">Kiemplant</option>
                    </select>
                </div>
                
                <div class="filterAfstand">
                    <label>Afstand</label>
                    <select class="selectAfstand" name="afstand">
                        <option value="">Geen voorkeur</option>
                        <option value="tien">< 10KM</option>
                        <option value="vijftien">< 15KM</option>
                        <option value="twintig">< 20 KM</option>
                    </select>
                </div>

                <div class="filterdatefrom">
                    <label>Van</label>
                    <input type="date" name="date_from" id="from">
                </div>
                
                <div class="filterdateto">
                    <label>Tot</label>
                    <input type="date" name="date_to" id="to">
                </div>

                <div class="submitfilters">
                    <input type="submit" name="submit-filters">
                </div>
            </div>
        </form>

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

        if(isset($_POST['submit-filters'])){
            $filterPlantsoort = $_POST['soort'];

            $filterAfstand = $_POST['afstand'];

            $filterDateFrom = $_POST['date_from'];
            $filterDfrom = strtotime($filterDateFrom);
            $filterDfrom = date("Y/m/d", $filterDfrom);

            $filterDateTo = $_POST['date_to'];
            $filterDTo = strtotime($filterDateTo);
            $filterDTo = date("Y/m/d", $filterDTo);


            if($filterPlantsoort != "" || $filterDateFrom != "" || $filterDateTo != "") {
                $sql = "SELECT * FROM Advertisement a JOIN User u ON a.userId = u.idUser JOIN AdImage ai ON a.idAd = ai.idAdvert WHERE plantCategory = '$filterPlantsoort' OR postDate >= '$filterDateFrom' AND postDate < '$filterDateTo' ORDER BY a.idAd DESC";
                $data = mysqli_query($conn, $sql) or die('error');
            }
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
                                <a class="linkPlant" href="adinfo?idAd='.$idAd.'">
                                    <div class="adImage">
                                        <img src="uploads/'.$adv["imgName"].'" alt="">
                                    </div>
                                    <div class="description">
                                        <h2>'.$plantName.'</h2>
                                        <br>
                                        <h3> Afstand: <span>'.$distance.'</span></h3>
                                        <h3> Datum: <span>'.$adDate.'</span></h3>
                                    </div>
                                </a>
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

