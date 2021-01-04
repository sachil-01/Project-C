<?php
    session_start();
    require "includes/dbh.inc.php";
    $userId = $_SESSION['userId'];

    if($_POST['function'] == "blogpost"){
        $blogpostId = $_POST['id'];
        $blogpostUser = $_POST['user'];

        //collect all image names
        $sql = "SELECT imgName FROM BlogImage WHERE idBlog = '$blogpostId'";
        $result = $conn->query($sql);                                    //make connection with database and run query
        $imgNames = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($imgNames, $row['imgName']);
            }
        }

        //delete all blogpost pictures from uploads folder
        foreach($imgNames as $imgName){
            $path = 'uploads/'.$imgName;
            unlink($path);
        }

        $sql = "DELETE FROM Blogcomments WHERE commentBlogId = '$blogpostId'";

        if ($conn->query($sql) === TRUE) {

            $sql = "DELETE FROM BlogImage WHERE idBlog = '$blogpostId'";

            if ($conn->query($sql) === TRUE) {

                $sql = "DELETE FROM Blogpost WHERE idPost = '$blogpostId'";
                
                if ($conn->query($sql) === TRUE) {
                    //if user clicks on delete button on blogpost page
                    if($blogpostUser == "blogpostDelete"){
                        echo "blogpost is verwijderd.";
                    } else {
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
                                    $adminBlogs = $adminBlogs . '<tr><td><p><span>Blogtitel: </span>'.$row["blogTitle"].'</p></td><td><p><span>Blog-id: </span>'.$row["idPost"].'</p></td><td><p><span>Release datum: </span>'.date_format(date_create($row["blogDate"]),"d-m-Y").'</p></td><td><button id="adminBlogpost" value='.$row["idPost"].' onclick="adminDeleteBlogpost(this.value, this.id)" class="Delete-btn">verwijder</button></td></tr>';
                                } else {
                                    $adminBlogs = $adminBlogs . '<tr><td><p><span>Blogtitel: </span>'.$row["blogTitle"].'</p></td><td><p><span>Release datum: </span>'.date_format(date_create($row["blogDate"]),"d-m-Y").'</p></td><td><button id="userBlogpost" value='.$row["idPost"].' onclick="adminDeleteBlogpost(this.value, this.id)" class="adDelete-btn">verwijder</button><a href="editAdOrBlog.php?blogpostId='.$row["idPost"].'"><button class="adEdit-btn">wijzig</button></a></td></tr>';
                                }
                            }
                        }
                        $adminBlogs = $adminBlogs . '</table>';                    //end html <table> tag
                        echo "$adminBlogs";
                    }
                } else {
                    echo "Error deleting record: " . $conn->error;
                }
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else if($_POST['function'] == "advertisement"){
        $advertisementId = $_POST['id'];
        $advertisementUser = $_POST['user'];

        //collect all image names
        $sql = "SELECT imgName FROM AdImage WHERE idAdvert = '$advertisementId'";
        $result = $conn->query($sql);                                    //make connection with database and run query
        $imgNames = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($imgNames, $row['imgName']);
            }
        }

        //delete all advertisement pictures from uploads folder
        foreach($imgNames as $imgName){
            $path = 'uploads/'.$imgName;
            unlink($path);
        }

        $sql = "DELETE FROM AdImage WHERE idAdvert = '$advertisementId'";

        if ($conn->query($sql) === TRUE) {

            $sql = "DELETE FROM Advertisement WHERE idAd = '$advertisementId'";
            
            if ($conn->query($sql) === TRUE) {
                //if user clicks on delete button on advertisement page
                if($advertisementUser == "advertisementDelete"){
//                    ### Hier code toevoegen voor mooier uiterlijk en link naar aanbod pagina ###
                    echo "Advertentie is verwijderd.";
                } else {
                    //check if its a registered user or an admin
                    if($advertisementUser == "adminAdvertisement"){
                        $sql = "SELECT plantName, idAd, postDate FROM Advertisement";
                        $adminAdvertisements = '<table class="ads-blogs-list"><tr class="ads-blogs-columnnames"><td><p>Advertentienaam</p></td><td><p>Advertentie-id</p></td><td><p>Geplaatst op</p></td><td><p>Verloopt op</p></td><td><p>Opties</p></td></tr>';
                    } else {
                        $sql = "SELECT a.plantName, a.idAd, a.postDate FROM Advertisement a WHERE a.userId = '$userId'";
                        $adminAdvertisements = '<table class="ads-blogs-list"><tr class="ads-blogs-columnnames"><td><p>Advertentienaam</p></td><td><p>Geplaatst op</p></td><td><p>Verloopt op</p></td><td><p>Opties</p></td></tr>';
                    }
                                                
                    $result = $conn->query($sql);                                    //make connection with database and run query
                    if ($result->num_rows > 0) {
                        // output data of each row
                        while ($row = $result->fetch_assoc()) {
                            $expiredDate = ($row['plantCategory'] == 'stekje' || $row['plantCategory'] == 'kiemplant') ? date_format(date_add(date_create($row["postDate"]), date_interval_create_from_date_string("2 months")), "d-m-Y") : date_format(date_add(date_create($row["postDate"]), date_interval_create_from_date_string("1 year")), "d-m-Y");
                            if($advertisementUser == "adminAdvertisement"){
                                $adminAdvertisements = $adminAdvertisements . '<tr><td><p><span>Advertentienaam: </span>'.$row["plantName"].'</p></td><td><p><span>Advertentie-id: </span>'.$row["idAd"].'</p></td><td><p><span>Release datum: </span>'.date_format(date_create($row["postDate"]),"d-m-Y").'</p></td><td><p><span>Verloopt op: </span>'.$expiredDate.'</p></td><td><button id="adminAdvertisement" value='.$row["idAd"].' onclick="adminDeleteAdvertisement(this.value, this.id)" class="Delete-btn">verwijder</button></td></tr>';
                            } else {
                                $adminAdvertisements = $adminAdvertisements . '<tr><td><p><span>Advertentienaam: </span>'.$row["plantName"].'</p></td><td><p><span>Release datum: </span>'.date_format(date_create($row["postDate"]),"d-m-Y").'</p></td><td><p><span>Verloopt op: </span>'.$expiredDate.'</p></td><td><button id="userAdvertisement" value='.$row["idAd"].' onclick="adminDeleteAdvertisement(this.value, this.id)" class="adDelete-btn">verwijder</button><a href="editAdOrBlog.php?advertisementId='.$row["idAd"].'"><button class="adEdit-btn">wijzig</button></a></td></tr>';
                            }
                        }
                    }
                    $adminAdvertisements = $adminAdvertisements . '</table>';                    //end html <table> tag
                    echo "$adminAdvertisements";
                }
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "niet gelukt";
    }
?>