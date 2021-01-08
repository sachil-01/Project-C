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
    <link rel="stylesheet" href="css/NewAdStyle.css">
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700|Montserrat:300" rel="stylesheet">
    <title>Fleurtop</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/fleurtop/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/fleurtop/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/fleurtop/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>
<body>
<nav>
       
            <a class="logo" href="index">
                <h2 class="nav-title">FLEURT </h2>
                &nbsp;
                <img src="images/Logo green2.png" alt="">
                <h2 class="nav-title">P</h2>
            </a>

            <ul class="nav-links">
                    <li><a href="adpagina">Aanbod</a></li>
                    <li><a href="blogpage">Blog</a></li>
                    <li><a href="hoewerkthet">Hoe werkt het?</a></li>
                    <li><a href="overons">Over ons</a></li>
                    <?php
                        $id = $_SESSION['userId'];
                        if (isset($id)) {
                            require 'includes/dbh.inc.php';
                            $sql = $conn->query("SELECT admin FROM User WHERE idUser = '$id'") or die($conn->error);
                            /* fetch associative array */
                            $row = $sql->fetch_assoc();
                            echo '<li><a href="profilepage"><i class="fas fa-user"></i> Mijn profiel</a></li>';
                            //if user is an admin, add dashboard tab to navbar
                            if($row['admin'] == 1){
                                echo '<li><a href="dashboard"><i class="fas fa-user-friends"></i> Dashboard</a></li>';
                            }
                            echo '<form action="includes/logout.inc.php" method="post">
                                    <li><button class="logout_button" type="submit" name="logout-submit">Uitloggen</button></li>
                                  </form>';
                        }
                        else {
                            echo '<li><a href="loginpagina">Login/Register</a></li>';
                        }
                    ?>
                    
                    
            </ul>

            <div class="burger">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
        </nav>
</body>
</html>