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
            $id = $_SESSION['userId'];
                    
            $sql = $conn->query("SELECT * FROM User WHERE idUser='$id'") or die($conn->error);

            /* fetch associative array */
            while ($row = $sql->fetch_assoc()) {
                $voornaam = $row["firstName"];
                $achternaam = $row["lastName"];
                $emailadres = $row["emailUser"];
                $gebruikersnaam = $row["usernameUser"];
                $straat = $row["streetName"];
                $huisnummer = $row["houseNumber"];
                $toevoeging = $row["houseNumberExtra"];
                $postcode = $row["postalCode"];
                $admin = $row["admin"];
                $biography = $row["biography"];
                $_SESSION["idUser"] = $row["idUser"];
            }

            //checks if user is an admin or not
            if($admin == 0){
                // fetch all required data of advertisements and blogposts and save the html of each in a string
                // string will be used in javascript function to load the right html when user switches between tabs
                $adArray = array();
                $blogArray = array();
                
                $sql = "SELECT a.plantName, a.postDate, a.idAd FROM User u JOIN Advertisement a ON u.idUser = a.userId WHERE u.idUser='$id'";
                $userAdvertisements = '<table class="ads-blogs-list"><tr class="ads-blogs-columnnames"><td><p>Advertentienaam</p></td><td><p>Geplaatst op</p></td><td><p>Verloopt op</p></td><td><p>Opties</p></td></tr>';
                array_push($adArray, $sql, $userAdvertisements);
                
                $sql = "SELECT b.blogTitle, b.idPost, b.blogDate FROM User u JOIN Blogpost b ON u.idUser = b.blogUserId WHERE u.idUser='$id'";
                $userBlogs = '<table class="ads-blogs-list"><tr class="ads-blogs-columnnames"><td><p>Blogtitel</p></td><td><p>Geplaatst op</p></td><td><p>Opties</p></td></tr>';
                array_push($blogArray, $sql, $userBlogs);

                $adBlogArray = array($adArray, $blogArray);
                
                $countAds = 0;
                $countBlogs = 0;
                for($i = 0; $i < count($adBlogArray[$i]); $i++){                                    //loop through advertisement array or blogpost array
                    $result = $conn->query($adBlogArray[$i][0]);                                    //make connection with database and run query
                    if ($result->num_rows > 0) {
                        // output data of each row
                        while ($row = $result->fetch_assoc()) {
                            if($i == 0){                                                           //checks if it's the advertisement array 
                                $expiredDate = ($row['plantCategory'] == 'stekje' || $row['plantCategory'] == 'kiemplant') ? date_format(date_add(date_create($row["postDate"]), date_interval_create_from_date_string("2 months")), "d-m-Y") : date_format(date_add(date_create($row["postDate"]), date_interval_create_from_date_string("1 year")), "d-m-Y");                                                                                                                                
                                $adBlogArray[$i][1] = $adBlogArray[$i][1] . '<tr><td><p><span>Advertentienaam: </span>'.$row["plantName"].'</p></td><td><p><span>Release datum: </span>'.date_format(date_create($row["postDate"]),"d-m-Y").'</p></td><td><p><span>Verloopt op: </span>'.$expiredDate.'</p></td><td><button id="userAdvertisement" value='.$row["idAd"].' onclick="adminDeleteAdvertisement(this.value, this.id)" class="adDelete-btn">verwijder</button><a href="editAdOrBlog.php?advertisementId='.$row["idAd"].'"><button class="adEdit-btn">wijzig</button></a></td></tr>';
                                $countAds++;
                            } else {
                                $adBlogArray[$i][1] = $adBlogArray[$i][1] . '<tr><td><p><span>Blogtitel: </span>'.$row["blogTitle"].'</p></td><td><p><span>Release datum: </span>'.date_format(date_create($row["blogDate"]),"d-m-Y").'</p></td><td><button id="userBlogpost" value='.$row["idPost"].' onclick="adminDeleteBlogpost(this.value, this.id)" class="adDelete-btn">verwijder</button><a href="editAdOrBlog.php?blogpostId='.$row["idPost"].'"><button class="adEdit-btn">wijzig</button></a></td></tr>';
                                $countBlogs++;
                            }
                        }
                    }
                    $adBlogArray[$i][1] = $adBlogArray[$i][1] . '</table>';                         //end html <table> tag
                }

                $userAdvertisements = $adBlogArray[0][1];
                $userBlogs = $adBlogArray[1][1];
            } else { //load all result lists for admin -> users, advertisements, blogposts
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
                    }
                    $userAdBlogArray[$i][1] = $userAdBlogArray[$i][1] . '</table>';                    //end html <table> tag
                }

                $adminUsers = $userAdBlogArray[0][1];
                $adminAdvertisements = $userAdBlogArray[1][1];
                $adminBlogs = $userAdBlogArray[2][1];
            }

            if (isset($_GET['error'])) {
                if ($_GET['error'] == "emptyfields") {
                    echo '<div class="profileError"><p>Vul alle velden in!</p></div>';
                }
                else if ($_GET['error'] == "invalidmailuid") {
                    echo '<div class="profileError"><p>Foutieve email en gebruikersnaam</p></div>';
                }
                else if ($_GET['error'] == "invaliduid") {
                    echo '<div class="profileError"><p>Foutieve gebruikersnaam</p></div>';
                }
                else if ($_GET['error'] == "invalidmail") {
                    echo '<div class="profileError"><p>Foutieve email</p></div>';
                }
                else if ($_GET['error'] == "passwordcheck") {
                    echo '<div class="profileError"><p>Uw wachtwoorden komen niet overeen</p></div>';
                }
                else if ($_GET['error'] == "usertaken") {
                    echo '<div class="profileError"><p>Gebruikersnaam is al in gebruik</p></div>';
                }
                else if ($_GET['error'] == "emailtaken") {
                    echo '<div class="profileError"><p>Emailadres is al in gebruik</p></div>';
                }
            }
            else if ($_GET['update'] == "success") {
                echo '<div class="profileError"><p>Update is gelukt !</p></div>';
            }

            if($admin == 0){    //profilebox of registered user
            ?>
            <table class="profilebox">
                <tr>
                    <div class="profilebox">
                        <h1>Mijn profiel</h1>
                    </div>
                </tr>
                <tr>
                <form action="includes/update.inc.php" method="post">
                    <td>
                        <div class="profilebox-left">
                            <div class="profile-leftpart-up">
                                <p>Beoordeling</p>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star"></span>
                                    <span class="fa fa-star"></span>
                                <p>Aantal advertenties</p>
                                <span><span id="totalads"></span><span id="starttotalads"><?php echo $countAds; ?></span> advertenties</span>
                                <p>Aantal blogposts</p>
                                <span><span id="totalblogs"></span><span id="starttotalblogs"><?php echo $countBlogs; ?></span> blogposts</span>
                            </div>
                            <div class="profile-leftpart-down">
                                <p>Biografie</p>
                                <textarea name="userBiography" placeholder="Voeg een biografie toe aan uw profiel"><?php echo $biography; ?></textarea>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="profilebox-right">
                            <p>Gebruikersnaam</p>
                            <input type="text" name="uid" value="<?php echo $gebruikersnaam; ?>" required>
                            <p>E-mail</p>
                            <input type="text" name="mail" value="<?php echo $emailadres; ?>" required>
                            <p>Voornaam</p>
                            <input type="text" name="firstName" value="<?php echo $voornaam; ?>" required>
                            <p>Achternaam</p>
                            <input type="text" name="lastName" value="<?php echo $achternaam; ?>" required>
                            <p>Straatnaam</p>
                            <input type="text" name="straatNaam" value="<?php echo $straat; ?>" required>
                            <p>Nummer</p>
                            <input type="number" name="huisNummer" value="<?php echo $huisnummer; ?>" required>
                            <p>Toevoeging</p>
                            <input type="text" name="toevoeging" value="<?php echo $toevoeging; ?>">
                            <p>Postcode</p>
                            <input type="text" name="postcode" value="<?php echo $postcode; ?>" required>
                        </div>
                    </td>
                </tr>
            </table>
            <?php
            } else { //profilebox of admin
            ?>
            <table class="profilebox">
                <tr>
                    <div class="profilebox">
                        <h1>Dashboard</h1>
                    </div>
                </tr>
                <tr>
                <form action="includes/update.inc.php" method="post">
                    <td>
                        <div class="dashboard">
                            <div class="dashboard-left">
                                <p>Gebruikersnaam</p>
                                <input type="text" name="uid" value="<?php echo $gebruikersnaam; ?>" required>
                                <p>E-mail</p>
                                <input type="text" name="mail" value="<?php echo $emailadres; ?>" required>
                                <p>Voornaam</p>
                                <input type="text" name="firstName" value="<?php echo $voornaam; ?>" required>
                                <p>Achternaam</p>
                                <input type="text" name="lastName" value="<?php echo $achternaam; ?>" required>
                            </div>
                            <div class="dashboard-right">
                                <p>Straatnaam</p>
                                <input type="text" name="straatNaam" value="<?php echo $straat; ?>" required>
                                <p>Nummer</p>
                                <input type="number" name="huisNummer" value="<?php echo $huisnummer; ?>" required>
                                <p>Toevoeging</p>
                                <input type="text" name="toevoeging" value="<?php echo $toevoeging; ?>">
                                <p>Postcode</p>
                                <input type="text" name="postcode" value="<?php echo $postcode; ?>" required>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
            <?php
            }
            ?>
            <!-- put update button under table -->
                    <div class="profilebox">
                        <button type="submit" name="update-submit">Wijzigingen opslaan</button>
                    </div>
                </form>

            <?php
            if($admin == 0){ 
            ?>
            <!-- User advertisements and blogposts switching tabs -->
            <div class="ads-blogs-box">
                <div class="ads-blogs-tabs">
                    <div id="ads-blogs-btns"></div>
                        <button id='ad-btn' class='ads-blogs-toggle-btn' onclick="leftClick()">Mijn advertenties</button>
                        <button id='blog-btn' class='ads-blogs-toggle-btn' onclick="rightClick()">Mijn blogposts</button>
                </div>
            </div>

            <div id="userAdsList">
                <?php
                    echo "$userAdvertisements";
                ?>
            </div>
            <div id="userBlogsList">
                <?php
                    echo "$userBlogs";
                ?>
            </div>
            <?php
            } else { //admin functions
            ?>
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
                        <!-- <br>
                        <input id="adminSearchInput" onkeyup="adminSearch()" class="admin-search" type="text" placeholder="Zoeken..">
                        <p id="adminSearchBtn">Zoeken</p> -->
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
            }
            ?>
        </body>

        <script>
            //blogpostId is the id of the blogpost stored in the button value
            function adminDeleteBlogpost(blogpostId, blogpostUser){
                $.ajax({
                    url: "adminFunctions.php",
                    type: 'post',
                    data: {function: "blogpost", id: blogpostId, user: blogpostUser},
                    success: function(result)
                    {
                        //checks which list needs to be updated
                        if(blogpostUser == "adminBlogpost"){ //admin blogpost list
                            document.getElementById("adminDisplayBlogpostFunc").innerHTML = result;
                        } else { // registered user blogpost list
                            document.getElementById("userBlogsList").innerHTML = result;
                            document.getElementById("totalblogs").innerHTML = (result.match(/Blogtitel:/g) || []).length;
                            document.getElementById("starttotalblogs").innerHTML = "";
                        }
                    }
                })
            }

            function adminDeleteAdvertisement(advertisementId, advertisementUser){
                $.ajax({
                    url: "adminFunctions.php",
                    type: 'post',
                    data: {function: "advertisement", id: advertisementId, user: advertisementUser},
                    success: function(result)
                    {
                        //checks which list needs to be updated
                        if(advertisementUser == "adminAdvertisement"){ //admin advertisement list
                            document.getElementById("adminDisplayAdvertisementFunc").innerHTML = result;
                        } else { // registered user advertisement list
                            document.getElementById("userAdsList").innerHTML = result;
                            document.getElementById("totalads").innerHTML = (result.match(/Advertentienaam:/g) || []).length;
                            document.getElementById("starttotalads").innerHTML = "";
                        }
                    }
                })
            }
        </script>

        <?php
    } else {
        echo'<div class="notloggedin">
                <h4>Om een blogpost te kunnen plaatsen moet u eerst ingelogd zijn. Klik <a href="loginpagina">HIER</a> om in te loggen.</h4>
            </div>';
    }

    include('footer.php');
    include('feedback.php');
?>