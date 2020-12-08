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
        <h1>Recent geuploade planten</h1>
        <div class="img-area">
        <?php 

        require 'includes/dbh.inc.php';

        $sql = "SELECT * FROM Advertisement a JOIN User u ON a.userId = u.idUser JOIN AdImage ai ON a.idAd = ai.idAdvert ORDER BY postDate DESC LIMIT 6";

        $statement = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($statement, $sql)) {
            header("Location: adpagina.php?error=sqlerror");
            echo '<div class="newposterror"><p>Er is iets fout gegaan (sql error).</p></div>';
        }
        else {
            mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);
            if ($row = mysqli_fetch_assoc($result)) {
                foreach ($result as $adv) {
                    $idAd = $adv['idAd'];
                    $plantName = $adv['plantName'];
                    $plantLatinName = $adv['plantLatinName'];
                    $adDate = date("d-m-Y", strtotime($adv['postDate']));
                    
                echo '<div class="plant">
                        <div class="adImage">
                        <a href="adinfo?idAd='.$idAd.'"><img src="uploads/'.$adv["imgName"].'" alt=""></a>
                        </div>
                        <div class="description">
                            <h2>'.$plantName.'</h2>
                            <br>
                            <h3> Afstand: <span>0km</span></h3>
                            <h3> Datum: <span>'.$adDate.'</span></h3>
                        </div>
                    </div>';
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
