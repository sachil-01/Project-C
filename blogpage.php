<?php
    include('header.php');
?>

<head>
    <title>Blogposts</title>
    <link rel="stylesheet" type="text/css" href="css\BlogStyle.css">
    <link rel="stylesheet" type="text/css" href="css\style.css">
</head>

<body>
    <div class="blogwrapper">
        <div class="blogheader">
            <h1>Blogposts</h1>
            <form action="newpost.php" method="post">
                <button class="submit" name="add-post"><i class="fas fa-plus"></i> Upload nieuwe blogpost</button>
            </form>  
        </div>
        <div class="blogcontainer">
            <div class="filters" style="margin-bottom: 5%">
                <h2 style="margin-bottom: 2%">Categorieën</h2>
                <div class="checkboxplantsoort">
                    <label><input type="checkbox" name="cate[]" value="verzorging" onchange="filterBlogposts(this.value)">Verzorging</label><br>
                    <label><input type="checkbox" name="cate[]" value="speciale evenementen" onchange="filterBlogposts(this.value)">Speciale evenementen</label><br>
                    <label><input type="checkbox" name="cate[]" value="vieringen en feestdagen" onchange="filterBlogposts(this.value)">Vieringen en feestdagen</label><br>
                </div>
            </div>
            <div class="grid-3-col" id="blogpostGallery">
                <?php
                require 'includes/dbh.inc.php';

                $sql = "SELECT * FROM Blogpost b JOIN User u ON b.blogUserId = u.idUser LEFT JOIN BlogImage bi ON b.idPost = bi.idBlog ORDER BY b.idPost DESC";

                $number_of_posts = $result->num_rows;
                //array with all blogpost Ids
                $allIdPosts = array();
                $result = $conn->query($sql);
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
                                        <p class="shortBlogDesc">'.$row["blogDesc"].'</p>
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
                    echo "0 resultaten";
                }
                $conn->close();
                ?>
            </div>
        </div>
    </div>
</body>
<script>
    var allCheckedFilters = [];
    function filterBlogposts(value) {
        if(!allCheckedFilters.includes(value)){
            allCheckedFilters.push(value);
        } else {
            for(i = 0; i < allCheckedFilters.length; i++) {
                if (allCheckedFilters[i] == value) {
                    allCheckedFilters.splice(i, 1);
                    break;
                }
            }
        }
        $.ajax({
            url: "blogFilter.php",
            type: 'post',
            data: { filters: allCheckedFilters},
            success: function(result)
            {
                document.getElementById("blogpostGallery").innerHTML = result;
            }
        })
    }
</script>
<?php 
    include('footer.php');
    include('feedback.php');
?>