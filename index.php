<?php
    include('header.php');
?>
<!DOCTYPE html>
<html lang="en">
<body>
    <div class="container">
        <!-- welcome -->
        <div class="welcome-div">
            <h1 class="nav-title">Naam website</h1>
            <a class="nav-title">"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</a>
            <br>
            <img src="images/Logo green.png" alt="">    
        </div>

        <?php 
            // Bericht zodat je kan zien of het werkt, later weghalen want is lelijk
                if (isset($_SESSION['userId'])) {
                    echo '<p>You are logged in !</p>';
                }
                else {
                    echo '<p>You are logged out !</p>';
                }

            ?>

        <div class="searchbar-div">
            <div class="searchbar-margin">
                <div class="searchbar-main">
                    <div class="searchbar-main-content">
                        <input type="search" class="searchbar-input" onfocus="this.value=''" placeholder="Zoeken...">
                    </div>
                </div>
            </div>
        </div>
        <!-- Planten homepage -->
        <div class="plant">
            <img src="images/plant1.jpg" alt="">
        </div>

        <div class="plant">
            <img src="images/plant2.jpg" alt="">
        </div>
        
        <div class="plant">
            <img src="images/plant4.jpg" alt="">
        </div>

        <div class="plant">
            <img src="images/plant4.jpg" alt="">
        </div>

        <div class="plant">
            <img src="images/plant4.jpg" alt="">
        </div>

        <div class="plant">
            <img src="images/plant4.jpg" alt="">
        </div>
    </div>
</body>
</html>

<?php 
    include('footer.php')
?>
