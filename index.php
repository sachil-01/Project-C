<?php
    include('header.php');
?>
<!DOCTYPE html>
<html lang="en">
<body>
    <div class="container">
        <!-- welcome -->
        <div class="welcome-div">
            <h2 class="nav-title">Welkom bij</h2>
            <h1 class="nav-title">Fleurt Op</h1>
            <p class="nav-title">"Een plek waar plantenliefhebbers bij elkaar kunnen komen om met elkaar te ruilen of van elkaar te leren met als doel het bevorderen van de biodiversiteit in Rotterdam."</p>
        </div>

        <div class="welcome-bg">
                <img src="images/RotterdamSkyline.jpg" alt="">   
        </div>

        <?php 
            // Bericht zodat je kan zien of het werkt, later weghalen want is lelijk
                if (isset($_SESSION['userId'])) {
                    // echo "<p>Hoi " . $_SESSION['userId'] . "</p>" ;
                }
                else {

                }

            ?>

        <!-- <div class="searchbar-div">
            <div class="searchbar-margin">
                <div class="searchbar-main">
                    <div class="searchbar-main-content">
                        <input type="search" class="searchbar-input" onfocus="this.value=''" placeholder="Zoeken...">
                    </div>
                </div>
            </div>
        </div> -->
        <!-- Planten homepage -->
        <div class="gallery">
            <h1>Nieuwste aanbiedingen</h1>
            <div class="img-area">
                <div class="plant">
                    <div class="adImage">
                        <img src="images/plant1.jpg" alt="">
                    </div>
                    <div class="description">
                        <h2>plantennaam</h2>
                        <br>
                        <h3>Afstand:</h3><p>0km</p>
                        <h3>Datum:</h3><p>ddmmyy</p>
                    </div>
                </div>

                <div class="plant">
                    <div class="adImage">
                        <img src="images/plant2.jpg" alt="">
                    </div>
                    <div class="description">
                        <h2>Plantennaam</h2>
                        <br>
                        <h3>Afstand:</h3><p>0km</p>
                        <h3>Datum:</h3><p>ddmmyy</p>
                    </div>
                </div>
                
                <div class="plant">
                    <div class="adImage">
                        <img src="images/plant3.jpg" alt="">
                    </div>
                    <div class="description">
                        <h2>Plantennaam</h2>
                        <br>
                        <h3>Afstand:</h3><p>0km</p>
                        <h3>Datum:</h3><p>ddmmyy</p>
                    </div>
                </div>

                <div class="plant">
                    <div class="adImage">
                        <img src="images/plant4.jpg" alt="">
                    </div>  
                    <div class="description">
                        <h2>Plantennaam</h2>
                        <br>
                        <h3>Afstand:</h3><p>0km</p>
                        <h3>Datum:</h3><p>ddmmyy</p>
                    </div>
                </div>

                <div class="plant">
                    <div class="adImage">
                        <img src="images/plant2.jpg" alt="">
                    </div>
                    <div class="description">
                        <h2>Plantennaam</h2>
                        <br>
                        <h3>Afstand:</h3><p>0km</p>
                        <h3>Datum:</h3><p>ddmmyy</p>
                    </div>
                </div>

                <div class="plant">
                    <div class="adImage">
                        <img src="images/plant1.jpg" alt="">
                    </div>
                    <div class="description">
                        <h2>Plantennaam</h2>
                        <br>
                        <h3>Afstand:</h3><p>0km</p>
                        <h3>Datum:</h3><p>ddmmyy</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php 
    include('footer.php')
?>
