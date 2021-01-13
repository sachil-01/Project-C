<?php
require 'includes/dbh.inc.php';
$category_filter = $_POST["filters"];



//array with all blogpost Ids
$allIdPosts = array();
$sql =  "SELECT * FROM Blogpost b JOIN User u ON b.blogUserId = u.idUser LEFT JOIN BlogImage bi ON b.idPost = bi.idBlog ";
if(!empty($category_filter)){
    $category_filter_sql = implode("','", $category_filter);
    $sql .= "WHERE b.blogCategory IN('".$category_filter_sql."')";
}
$sql .= " ORDER BY b.idPost DESC";

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