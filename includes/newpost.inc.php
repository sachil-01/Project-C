<?php
session_start();

if(isset($_POST["blog-submit"])) {
    require 'dbh.inc.php';

    //Process the image that is uploaded by the user

    // $target_dir = "uploads/";
    // $target_file = $target_dir.basename($_FILES["bImage"]["name"]);
    // $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

    // move_uploaded_file($_FILES["bImage"]["tmp_name"], $target_file);

    $blogtitle = $_POST["bname"];
    $blogcategory = $_POST["bcategory"];
    $blogdescription = $_POST["bdesc"];
    $blogImage = $_POST["bImage"];
    $blogLink = $_POST['bLink'];
    $userId = $_SESSION['userId'];

    // Insert blogpost data into database
    $sql = "INSERT INTO Blogpost(blogTitle, blogCategory, blogDesc, blogUserId, blogLink) VALUES (?, ?, ?, ?, ?)";
    $statement = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($statement, $sql)) {
        header("Location: ../newpost.php?error=sqlerror");
        exit();
    }
    else {
        mysqli_stmt_bind_param($statement, "sssis", $blogtitle, $blogcategory, $blogdescription, $userId, $blogLink);
        mysqli_stmt_execute($statement); 
    }

    // Retrieve blogpost ID before inserting blogpost image(s) to database
    $sql = "SELECT idPost FROM Blogpost WHERE blogUserId = $userId ORDER BY blogDate DESC LIMIT 1";
    $statement = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($statement, $sql)) {
        header("Location: ../newpost.php?error=sqlerror");
        exit();
    }
    else {
        mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);
            if ($row = mysqli_fetch_assoc($result)) {
                $blogId= $row['idPost'];
            }
    }

    // Insert blogpost image into database
    $sql = "INSERT INTO BlogImage(imgName, idBlog) VALUES (?, ?)";
    $statement = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($statement, $sql)) {
        header("Location: ../newpost.php?error=sqlerror");
        exit();
    }
    else {
        mysqli_stmt_bind_param($statement, "ss", $blogImage, $blogId);
        mysqli_stmt_execute($statement); 
        header("Location: ../newpost.php?upload=success");
        header("Location: ../blogpage.php");
        exit();
    }
}
?>