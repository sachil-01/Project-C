<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700|Montserrat:300" rel="stylesheet">
    <title>Stekjeruilen</title>
</head>
<body>
        <nav>
       
            <a class="logo" href="index.php">
                <h2>Naam/Logo</h2>
            </a>

            <ul class="nav-links">
                    <li><a href="adpagina.php">Advertenties</a></li>
                    <li><a href="#">Help Pagina</a></li>
<!--                    <li><input type="search" class="searchbar" onfocus="this.value=''" placeholder="Zoeken..."></i></li>-->
                    <?php
                        if (isset($_SESSION['userId'])) {
                            echo '<form action="includes/logout.inc.php" method="post">
                                    <button type="submit" name="logout-submit">Logout</button>
                                </form>';
                        }
                        else {
                            echo '<li><a href="loginpagina.php">Login/Register</a></li>';
                        }
                    ?>
                    
                    
            </ul>

            <div class="burger">
                        <div class="line1"></div>
                        <div class="line2"></div>
                        <div class="line3"></div>
            </div>
        </nav>
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<!--        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>-->
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <script src="main.js"></script>
</body>


</html>