<?php
    require "includes/dbh.inc.php";

    if(isset($_POST['id'])){
        $blogpostId = $_POST['id'];
    } else {
        $blogpostId = "blogpostId is nog niet ingesteld";
    }

    $sql = "DELETE FROM BlogImage WHERE idBlog = '$blogpostId'";

    if ($conn->query($sql) === TRUE) {

        $sql = "DELETE FROM Blogpost WHERE idPost = '$blogpostId'";
        
        if ($conn->query($sql) === TRUE) {
            $sql = "SELECT blogTitle, idPost, blogDate FROM Blogpost";
            $adminBlogs = '<table class="ads-blogs-list"><tr class="ads-blogs-columnnames"><td><p>Blogtitel</p></td><td><p>Blog-id</p></td><td><p>Geplaatst op</p></td><td><p>Opties</p></td></tr>';
                                        
            $result = $conn->query($sql);                                    //make connection with database and run query
            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    $adminBlogs = $adminBlogs . '<tr><td><p><span>Blogtitle: </span>'.$row["blogTitle"].'</p></td><td><p><span>Blog-id: </span>'.$row["idPost"].'</p></td><td><p><span>Release datum: </span>'.date_format(date_create($row["blogDate"]),"d-m-Y").'</p></td><td><button id="adminBlogpost" value='.$row["idPost"].' onclick="adminDeleteBlogpost(this.value)" class="adDelete-btn">verwijder</button><button class="adEdit-btn">wijzig</button></td></tr>';
                }
            }
            $adminBlogs = $adminBlogs . '</table>';                    //end html <table> tag
            echo "$adminBlogs";
        }
        else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "Error deleting record: " . $conn->error;
    }
?>