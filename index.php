<?php
    include('header.php');
?>
<!DOCTYPE html>
<html lang="en">
<body>
    <div class="container">
        <!-- welcome -->
        <table class= "welcome-div">
            <tr>
                <td>
                    <h2 class="nav-title">Welkom</h2>
                    <p class="nav-title">"Een plek waar plantenliefhebbers bij elkaar kunnen komen om met elkaar te ruilen of van elkaar te leren met als doel het bevorderen van de biodiversiteit in Rotterdam."</p>
                    <a href="hoewerkthet"><button class="Explore-btn">Ontdek meer</button></a>
                </td>
            </tr>
        </table>
    </div>



    <div class="gallery">
        <h1>Nieuwste aanbod</h1>
        <span class="scroll">

        </span>
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

        $sql = "SELECT * FROM Advertisement a JOIN User u ON a.userId = u.idUser JOIN AdImage ai ON a.idAd = ai.idAdvert ORDER BY postDate DESC";

        $statement = mysqli_stmt_init($conn);
        $allIdAdvertisements = array();
        if (!mysqli_stmt_prepare($statement, $sql)) {
            header("Location: adpagina.php?error=sqlerror");
            echo '<div class="newposterror"><p>Er is iets fout gegaan (sql error).</p></div>';
        }
        else {
            mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);
            if ($row = mysqli_fetch_assoc($result)) {
                $limitPosts = 6;
                $countPost = 0;
                foreach ($result as $adv) {
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
                        array_push($allIdAdvertisements, $adv['idAd']);
                        $countPost++;
                        if($countPost == $limitPosts){
                            break;
                        }
                    }
                }
            }
        };
        ?>
        </div>
    </div>
</body>
</html>

<?php 
    include('footer.php');
    include('feedback.php');
?>
