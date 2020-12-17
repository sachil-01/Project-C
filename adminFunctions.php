<?php
    session_start();
    require "includes/dbh.inc.php";
    $userId = $_SESSION['userId'];

    if(isset($_POST['id'])){
        $blogpostId = $_POST['id'];
        $blogpostUser = $_POST['user'];
    } else {
        $blogpostId = "blogpostId is nog niet ingesteld";
    }

    $sql = "DELETE FROM BlogImage WHERE idBlog = '$blogpostId'";

    if ($conn->query($sql) === TRUE) {

        $sql = "DELETE FROM Blogpost WHERE idPost = '$blogpostId'";
        
        if ($conn->query($sql) === TRUE) {
            //check if its a registered user or an admin
            if($blogpostUser == "adminBlogpost"){
                $sql = "SELECT blogTitle, idPost, blogDate FROM Blogpost";
                $adminBlogs = '<table class="ads-blogs-list"><tr class="ads-blogs-columnnames"><td><p>Blogtitel</p></td><td><p>Blog-id</p></td><td><p>Geplaatst op</p></td><td><p>Opties</p></td></tr>';
            } else {
                $sql = "SELECT blogTitle, idPost, blogDate FROM Blogpost WHERE blogUserId = '$userId'";
                $adminBlogs = '<table class="ads-blogs-list"><tr class="ads-blogs-columnnames"><td><p>Blogtitel</p></td><td><p>Geplaatst op</p></td><td><p>Opties</p></td></tr>';
            }
                                        
            $result = $conn->query($sql);                                    //make connection with database and run query
            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    if($blogpostUser == "adminBlogpost"){
                        $adminBlogs = $adminBlogs . '<tr><td><p><span>Blogtitle: </span>'.$row["blogTitle"].'</p></td><td><p><span>Blog-id: </span>'.$row["idPost"].'</p></td><td><p><span>Release datum: </span>'.date_format(date_create($row["blogDate"]),"d-m-Y").'</p></td><td><button id="adminBlogpost" value='.$row["idPost"].' onclick="adminDeleteBlogpost(this.value, this.id)" class="adDelete-btn">verwijder</button><button class="adEdit-btn">wijzig</button></td></tr>';
                    } else {
                        $adminBlogs = $adminBlogs . '<tr><td><p><span>Blogtitle: </span>'.$row["blogTitle"].'</p></td><td><p><span>Release datum: </span>'.date_format(date_create($row["blogDate"]),"d-m-Y").'</p></td><td><button id="userBlogpost" value='.$row["idPost"].' onclick="adminDeleteBlogpost(this.value, this.id)" class="adDelete-btn">verwijder</button><button class="adEdit-btn">wijzig</button></td></tr>';
                    }
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