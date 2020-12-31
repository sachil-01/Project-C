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
                require 'includes/dbh.inc.php';

                $sql = "SELECT * FROM Blogpost b JOIN User u ON b.blogUserId = u.idUser LEFT JOIN BlogImage bi ON b.idPost = bi.idBlog ORDER BY b.idPost DESC";
                $result = $conn->query($sql);
                $number_of_posts = $result->num_rows;
                //array with all blogpost Ids
                $allIdPosts = array();
                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                        //checks if blogpost id already exists in array > if blogpost id exists in array -> skip current blogpost
                        if(!in_array($row['idPost'], $allIdPosts)){
                            echo '<div class="blogpost">
                                    <a class="linkPlant" href="bloginfo?idBlog='.$row["idPost"].'">
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
                                    </a>
                                  </div>';
                            //add blogpost id to array
                            array_push($allIdPosts, $row['idPost']);
                        }
                    }
                } else {
                    echo "0 results";
                }
                $conn->close();
                ?>
            </div>
        </div>
    </div>
</body>

<?php 
    include('footer.php');
    include('feedback.php');
?>