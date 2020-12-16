<?php
    session_start();
    include('header.php');
?>
<head>
    <title>Blogpost form</title>
    <link rel="stylesheet" type="text/css" href="css\NewPostStyle.css">
</head>
<body>
    <?php
    if (isset($_SESSION['userId'])) {
        if(isset($_POST['blog-submit'])){
            require 'includes/dbh.inc.php';

            $allowed = ['png', 'jpg', 'gif', 'jpeg'];
            $fileCount = count($_FILES['file']['name']);

            //Check if user uploaded images
            for($i=0; $i < $fileCount; $i++){
                if(!in_array(strtolower(pathinfo($_FILES['file']['name'][$i], PATHINFO_EXTENSION)), $allowed)){
                    $imageFormats = false;
                    break;
                }
                $imageFormats = true;
            }

            if($imageFormats){
                $blogtitle = $_POST["bname"];
                $blogcategory = $_POST["bcategory"];
                //nl2br() function saves breaklines of user
                $blogdescription = nl2br($_POST["bdesc"]);
                $blogLink = $_POST['bLink'];
                $userId = $_SESSION['userId'];

                // Insert blogpost data into database
                $sql = "INSERT INTO Blogpost(blogTitle, blogCategory, blogDesc, blogUserId, blogLink) VALUES (?, ?, ?, ?, ?)";
                $statement = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($statement, $sql)) {
                    header("Location: newpost.php?error=sqlerror");
                    echo '<div class="newposterror"><p>Er is iets fout gegaan (sql error).</p></div>';
                }
                else {
                    mysqli_stmt_bind_param($statement, "sssis", $blogtitle, $blogcategory, $blogdescription, $userId, $blogLink);
                    mysqli_stmt_execute($statement); 
                }

                // Retrieve blogpost ID before inserting blogpost image(s) to database
                $sql = "SELECT idPost FROM Blogpost WHERE blogUserId = $userId ORDER BY blogDate DESC LIMIT 1";
                $statement = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($statement, $sql)) {
                    header("Location: newpost.php?error=sqlerror");
                    echo '<div class="newposterror"><p>Er is iets fout gegaan (sql error).</p></div>';
                }
                else {
                    mysqli_stmt_execute($statement);
                    $result = mysqli_stmt_get_result($statement);
                    if ($row = mysqli_fetch_assoc($result)) {
                        $blogId= $row['idPost'];
                    }
                }

                //inserts image in uploads folder
                for($i=0; $i < $fileCount; $i++){
                    //checks if there is a file uploaded
                    if(UPLOAD_ERR_OK == $_FILES['file']['error'][$i]){
                        //give filename a time to avoid overwriting a file (unique name)
                        $fileName = time().$_FILES['file']['name'][$i];

                        //insert image to database
                        $sql = "INSERT INTO BlogImage(imgName, idBlog) VALUES (?, ?)";
                        $statement = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($statement, $sql)) {
                            header("Location: newpost.php?error=sqlerror");
                            echo '<div class="newposterror"><p>Er is iets fout gegaan (sql error).</p></div>';
                        }
                        else {
                            mysqli_stmt_bind_param($statement, "ss", pathinfo($fileName, PATHINFO_BASENAME), $blogId);
                            mysqli_stmt_execute($statement); 
                            
                            //insert image to uploads folder
                            move_uploaded_file($_FILES['file']['tmp_name'][$i], 'uploads/'.$fileName);
                        }
                    }
                }
                header("Location: newpost.php?upload=success");
                echo '<div class="newposterror"><p>Uw blogpost is succesvol geupload!</p></div>';
            } else {
                echo '<div class="newposterror"><p>Alleen "jpg", "png", "gif" en "jpeg" bestanden zijn toegestaan!</p></div>';
            }
        }
    ?>
    <div class="blogpostform">
        <h2>Nieuwe blogpost</h2><br>
        <form action="" method="post" enctype="multipart/form-data">
            <label>Blogtitel</label><br>
            <input type="text" id="bname" name="bname" required><br><br>
            
            <label>Blogcategorie</label><br>
            <select name="bcategory" id="bcategory">
                <option value="verzorging">Verzorging</option>
                <option value="speciale evenementen">Speciale evenementen</option>
                <option value="vieringen en feestdagen">Vieringen en feestdagen</option>
            </select><br><br>

            <label>Beschrijving</label><br>
            <textarea id="bdesc" name="bdesc" required></textarea><br><br>

            <label>Afbeeldingen</label><br>
            <input type='file' name='file[]' id='file' multiple><br><br>

            <label>URL toevoegen</label><br>
            <input type="url" name="bLink" id="bLink"><br><br>
            <input class="newPostButton" type="submit" name="blog-submit" value="Blogpost plaatsen">
        </form>
    </div>
    <?php
    } else {
        echo'<div class="notloggedin">
                <h4>Om een blogpost te kunnen plaatsen moet u eerst ingelogd zijn. Klik <a href="loginpagina">HIER</a> om in te loggen.</h4>
            </div>';
    }
    ?>
</body>

<?php
    include('footer.php');
    include('feedback.php');
?>