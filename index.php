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
                    <h2 class="nav-title">Welkom bij</h2>
                    <h1 class="nav-title">Fleurt Op</h1>
                    <p class="nav-title">"Een plek waar plantenliefhebbers bij elkaar kunnen komen om met elkaar te ruilen of van elkaar te leren met als doel het bevorderen van de biodiversiteit in Rotterdam."</p>
                    <button class="Explore-btn">Ontdek meer</button>
                </td>
                <td>
                    <div class="gallery">
                        <div class="img-area slidertns">
                            <div>
                                <div class="adImage">
                                    <img src="images/plant1.jpg" alt="">
                                </div>
                                <div class="description">
                                    <h2>plantennaam</h2>
                                    <h3> Afstand: <span>0km</span></h3>
                                    <h3> Datum: <span>ddmmyy</span></h3>
                                </div>
                            </div>

                            <div>
                                <div class="adImage">
                                    <img src="images/plant2.jpg" alt="">
                                </div>
                                <div class="description">
                                    <h2>plantennaam</h2>
                                    <h3> Afstand: <span>0km</span></h3>
                                    <h3> Datum: <span>ddmmyy</span></h3>
                                </div>
                            </div>

                            <div>
                                <div class="adImage">
                                    <img src="images/plant3.jpg" alt="">
                                </div>
                                <div class="description">
                                    <h2>plantennaam</h2>
                                    <h3> Afstand: <span>0km</span></h3>
                                    <h3> Datum: <span>ddmmyy</span></h3>
                                </div>
                            </div>

                            <div>
                                <div class="adImage">
                                    <img src="images/plant4.jpg" alt="">
                                </div>
                                <div class="description">
                                    <h2>plantennaam</h2>
                                    <h3> Afstand: <span>0km</span></h3>
                                    <h3> Datum: <span>ddmmyy</span></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <?php 
        // // Bericht zodat je kan zien of het werkt, later weghalen want is lelijk
        // if (isset($_SESSION['userId'])) {
        //     // echo "<p>Hoi " . $_SESSION['userId'] . "</p>" ;
        // }
        // else {

        // }
    ?>

</body>
</html>

<?php 
    include('footer.php')
?>
