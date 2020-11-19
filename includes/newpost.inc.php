<?php
session_start();

if(isset($_POST["blog-submit"])) {
    require 'dbh.inc.php';

    $blogtitle = $_POST["bname"];
    $blogcategory = $_POST["bcategory"];
    $blogdescription = $_POST["bdesc"];
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
    $blogImage = $_SESSION['images'];

    // Insert blogpost image into database
    foreach($blogImage as $fileName){
        $sql = "INSERT INTO BlogImage(imgName, idBlog) VALUES (?, ?)";
        $statement = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($statement, $sql)) {
            header("Location: ../newpost.php?error=sqlerror");
            exit();
        }
        else {
            mysqli_stmt_bind_param($statement, "ss", pathinfo($fileName, PATHINFO_FILENAME), $blogId);
            mysqli_stmt_execute($statement); 
        }
    }

    header("Location: ../newpost.php?upload=success");
    exit();
}
?>