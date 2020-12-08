<?php
    include('header.php');
?>

<head>
    <title>Blogposts</title>
    <link rel="stylesheet" type="text/css" href="css\BlogStyle.css">
</head>

<body>
    <div class="blogwrapper">
        <div class="blogheader">
            <h1>Blogposts</h1>
            <div class="category-select">
                <select>
                    <option>Alle</option>
                    <option>Verzorging</option>
                    <option>Speciale evenementen</option>
                    <option>Vieringen en feestdagen</option>
                </select>
            </div>
            <form action="newpost.php" method="post">
                <button class="submit" name="add-post"><i class="fas fa-plus"></i> Upload nieuwe blogpost</button>
            </form>  
        </div>
        <div class="blogcontainer">
            <div class="blogcategories">
                    <h2>Categorieën</h2>
                    <li><a href="#">Alle</a></li>
                    <li><a href="#">Verzorging</a></li>
                    <li><a href="#">Speciale evenementen</a></li>
                    <li><a href="#">Vieringen en feestdagen</a></li>
            </div>
            <div class="grid-3-col">
            <?php

            $servername = "localhost";
            $dBUsername = "royvan1q_user_dekas";
            $dBPassword = "Bossex123!";
            $dBName = "royvan1q_websitedekas";

            $conn = new mysqli($servername, $dBUsername, $dBPassword, $dBName);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM Blogpost b JOIN User u ON b.blogUserId = u.idUser JOIN BlogImage bi ON b.idPost = bi.idBlog ORDER BY b.idPost DESC";
            $result = $conn->query($sql);
            $number_of_posts = $result->num_rows;
            if ($result->num_rows > 0) {
                // output data of each row

                while ($row = $result->fetch_assoc()) {
                    echo '<div class="blogpost">
                        <div class="blogImage">
                            <img src="uploads/'.$row["imgName"].'" alt="">
                        </div>
                        <div class="blogDescription">
                            <h2>'.$row["blogTitle"].'</h2>
                            <h3>'.$row["firstName"].'</h3>
                            <p>'.$row["blogDesc"].'</p>
                            <h4 class="alignleft">'.date_format(date_create($row["blogDate"]),"d-m-Y").'</h4>
                            <h4 class="alignright">'.$row["blogCategory"].'</h4>
                        </div>
                    </div>';
                }

            } else {
                echo "0 results";
            }
            $conn->close();
            ?>
            </div>
<!--            <div class="grid-3-col">-->
<!--                    <div class="blogpost">-->
<!--                        <div class="blogImage">-->
<!--                            <img src="images/plant2.jpg" alt="">-->
<!--                        </div>-->
<!--                        <div class="blogDescription">-->
<!--                            <h2>Titel van blog</h2>-->
<!--                            <h3>Auteur</h3>-->
<!--                            <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat."</p>-->
<!--                            <h4 class="alignleft">00-00-0000</h4>-->
<!--                            <h4 class="alignright">Speciale evenementen</h4>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                <div class="blogpost">-->
<!--                    <div class="blogImage">-->
<!--                        <img src="images/plant3.jpg" alt="">-->
<!--                    </div>-->
<!--                    <div class="blogDescription">-->
<!--                        <h2>Titel van blog</h2>-->
<!--                        <h3>Auteur</h3>-->
<!--                        <p>Voorbeeld tekst voor blogpost</p>-->
<!--                        <h4 class="alignleft">00-00-0000</h4>-->
<!--                        <h4 class="alignright">Vieringen en feestdagen</h4>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="blogpost">-->
<!--                    <div class="blogImage">-->
<!--                        <img src="images/plant4.jpg" alt="">-->
<!--                    </div>-->
<!--                    <div class="blogDescription">-->
<!--                        <h2>Titel van blog</h2>-->
<!--                        <h3>Auteur</h3>-->
<!--                        <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>-->
<!--                        <h4 class="alignleft">00-00-0000</h4>-->
<!--                        <h4 class="alignright">Verzorging</h4>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="blogpost">-->
<!--                    <div class="blogImage">-->
<!--                        <img src="images/plant2.jpg" alt="">-->
<!--                    </div>-->
<!--                    <div class="blogDescription">-->
<!--                        <h2>Titel van blog</h2>-->
<!--                        <h3>Auteur</h3>-->
<!--                        <p>Voorbeeld tekst voor blogpost</p>-->
<!--                        <h4 class="alignleft">00-00-0000</h4>-->
<!--                        <h4 class="alignright">Vieringen en feestdagen</h4>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        </div>
    </div>
</body>

<?php 
    include('footer.php');
    include('feedback.php');
?>