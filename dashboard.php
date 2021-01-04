<!DOCTYPE html>
<?php
    session_start();
    require 'header.php';
?>
<head>
    <link rel="stylesheet" href="css/ProfileStyle.css">
    <script scr="main.js"></script>
</head>
<body>

<?php
    if (isset($_SESSION['userId'])) {
        require 'includes/dbh.inc.php';

        $sql = $conn->query("SELECT * FROM User WHERE idUser='$id'") or die($conn->error);

        /* fetch associative array */
        $row = $sql->fetch_assoc();
        if($row['admin'] == 1){
            $id = $_SESSION['userId'];
            //load all result lists for admin -> users, advertisements, blogposts
            // fetch all required data of advertisements, users and blogposts and save the html of each in a string
            // string will be used in javascript function to load the right html when user switches between tabs
            $userArray = array();
            $adArray = array();
            $blogArray = array();

            $sql = "SELECT u.usernameUser, u.idUser FROM User u";
            $adminUsers = '<table class="ads-blogs-list"><tr class="ads-blogs-columnnames"><td><p>Gebruikersnaam</p></td><td><p>Gebruikers-id</p></td><td><p>Opties</p></td></tr>';
            array_push($userArray, $sql, $adminUsers);
            
            $sql = "SELECT a.plantName, a.idAd, a.postDate FROM Advertisement a";
            $adminAdvertisements = '<table class="ads-blogs-list"><tr class="ads-blogs-columnnames"><td><p>Advertentienaam</p></td><td><p>Advertentie-id</p></td><td><p>Geplaatst op</p></td><td><p>Verloopt op</p></td><td><p>Opties</p></td></tr>';
            array_push($adArray, $sql, $adminAdvertisements);
            
            $sql = "SELECT b.blogTitle, b.idPost, b.blogDate FROM Blogpost b";
            $adminBlogs = '<table class="ads-blogs-list"><tr class="ads-blogs-columnnames"><td><p>Blogtitel</p></td><td><p>Blog-id</p></td><td><p>Geplaatst op</p></td><td><p>Opties</p></td></tr>';
            array_push($blogArray, $sql, $adminBlogs);

            $userAdBlogArray = array($userArray, $adArray, $blogArray);
            
            for($i = 0; $i <= count($userAdBlogArray[$i]); $i++){                                    //loop through advertisement array, user array or blogpost array
                $result = $conn->query($userAdBlogArray[$i][0]);                                    //make connection with database and run query
                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                        if($i == 0){                                                                //checks if it's the users array
                            $userAdBlogArray[$i][1] = $userAdBlogArray[$i][1] . '<tr><td><p><span>Gebruikersnaam: </span>'.$row["usernameUser"].'</p></td><td><p><span>Gebruikers-id: </span>'.$row["idUser"].'</p></td><td><button class="Delete-btn">verwijder</button></td></tr>';
                        } else if($i == 1){                                                         //checks if it's the advertisements array
                            $expiredDate = ($row['plantCategory'] == 'stekje' || $row['plantCategory'] == 'kiemplant') ? date_format(date_add(date_create($row["postDate"]), date_interval_create_from_date_string("2 months")), "d-m-Y") : date_format(date_add(date_create($row["postDate"]), date_interval_create_from_date_string("1 year")), "d-m-Y");        
                            $userAdBlogArray[$i][1] = $userAdBlogArray[$i][1] . '<tr><td><p><span>Advertentienaam: </span>'.$row["plantName"].'</p></td><td><p><span>Advertentie-id: </span>'.$row["idAd"].'</p></td><td><p><span>Release datum: </span>'.date_format(date_create($row["postDate"]),"d-m-Y").'</p></td><td><p><span>Verloopt op: </span>'.$expiredDate.'</p></td><td><button id="adminAdvertisement" value='.$row["idAd"].' onclick="adminDeleteAdvertisement(this.value, this.id)" class="Delete-btn">verwijder</button></td></tr>';
                        } else{                                                                   //blogposts array
                            $userAdBlogArray[$i][1] = $userAdBlogArray[$i][1] . '<tr><td><p><span>Blogtitel: </span>'.$row["blogTitle"].'</p></td><td><p><span>Blog-id: </span>'.$row["idPost"].'</p></td><td><p><span>Release datum: </span>'.date_format(date_create($row["blogDate"]),"d-m-Y").'</p></td><td><button id="adminBlogpost" value='.$row["idPost"].' onclick="adminDeleteBlogpost(this.value, this.id)" class="Delete-btn">verwijder</button></td></tr>';
                        }
                    }
                } else {
                    $userAdBlogArray[$i][1] = "<p class='emptyAdOrBlogList'>0 resultaten.</p>";
                }
                $userAdBlogArray[$i][1] = $userAdBlogArray[$i][1] . '</table>';                    //end html <table> tag
            }

            $adminUsers = $userAdBlogArray[0][1];
            $adminAdvertisements = $userAdBlogArray[1][1];
            $adminBlogs = $userAdBlogArray[2][1];
            ?>
            <div class="profilebox">
                <h1>Dashboard</h1>
            </div>
            <div class="admin-container" id="adminDisplayFuncList">
                <div class="admin-row">
                    <div class="admin-func">
                        <div class="details" onclick="adminDisplayFunc('adminDisplayUserFunc')" id="adminUserFunc">
                            <h2>Gebruikers<h2>
                            <p>Klik hier om gebruikers te verwijderen</p>
                            <img src="images/userIcon3.png" alt="">
                        </div>
                    </div>
                    <div class="admin-func" onclick="adminDisplayFunc('adminDisplayAdvertisementFunc')" id="adminAdvertisementFunc">
                        <div class="details">
                            <h2>Advertenties<h2>
                            <p>Klik hier om advertenties te verwijderen</p>
                            <img src="images/userAds.png" alt="">
                        </div>
                    </div>
                    <div class="admin-func" onclick="adminDisplayFunc('adminDisplayBlogpostFunc')" id="adminBlogpostFunc">
                        <div class="details">
                            <h2>Blogposts<h2>
                            <p>Klik hier om blogposts te verwijderen</p>
                            <img src="images/userBlog2.png" alt="">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ADMIN FUNCTIONS -->
            <div class="chosen-admin-func-container" id="adminDisplayResultList">
                <div class="admin-row">
                    <div class="chosen-admin-func">
                        <div class="admin-func-header">
                            <button id="backToAllAdminFunc" onclick="adminDisplayAllFunc()">Terug naar het overzicht</button>
                            <h2 id="chosenAdminFuncTitle"><h2>
                        </div>
                        <br>
                        <div id="adminDisplayUserFunc">
                            <?php
                                echo "$adminUsers";
                            ?>
                        </div>
                        <div id="adminDisplayAdvertisementFunc">
                            <?php
                                echo "$adminAdvertisements";
                            ?>
                        </div>
                        <div id="adminDisplayBlogpostFunc">
                            <?php
                                echo "$adminBlogs";
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        } else {
            echo'<div class="notloggedin">
                    <h4>Alleen admin accounts hebben toegang tot deze pagina.</h4>
                 </div>';
        }
    } else {
        echo'<div class="notloggedin">
                <h4>Om een blogpost te kunnen plaatsen moet u eerst ingelogd zijn. Klik <a href="loginpagina">HIER</a> om in te loggen.</h4>
            </div>';
    }

    include('footer.php');
    include('feedback.php');
?>