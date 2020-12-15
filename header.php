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
</head>
<body>
<nav>
       
            <a class="logo" href="index">
                <h2 class="nav-title">Fleurt </h2>
                &nbsp;
                <img src="images/Logo green2.png" alt="">
                <h2 class="nav-title">p</h2>
            </a>

            <ul class="nav-links">
                    <li><a href="adpagina">Aanbod</a></li>
                    <li><a href="blogpage">Blog</a></li>
                    <li><a href="hoewerkthet">Hoe werkt het?</a></li>
                    <?php
                        $id = $_SESSION['userId'];
                        if (isset($id)) {
                            require 'includes/dbh.inc.php';
                            $sql = $conn->query("SELECT admin FROM User WHERE idUser = '$id'") or die($conn->error);
                            /* fetch associative array */
                            while ($row = $sql->fetch_assoc()) {
                                if($row["admin"] == 1){
                                    $user = " Dashboard";
                                } else if ($row["admin"] == 0){
                                    $user = " Mijn profiel";
                                }
                            }
                            $_SESSION['admin'] = $row["admin"];
                            echo '<li><a href="profilepage.php"><i class="fas fa-user"></i>'.$user.'</a><li>';
                            echo '<form action="includes/logout.inc.php" method="post">
                                    <button class="logout_button" type="submit" name="logout-submit">Logout</button>
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