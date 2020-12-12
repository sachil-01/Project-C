<?php
    session_start();
    if(isset($_POST['comment-submit'])){
        require 'dbh.inc.php';

        $blogId = $_SESSION['blogId'];
        $userId = $_SESSION['userId'];
        $comment = $_POST["comment-input"];

        //limit userinput
        $comment_length = strlen($comment);

        if($comment_length > 600){
            echo "Uw commentaar is te lang!";
        } else {
            // Insert blogpost data into database
            $sql = "INSERT INTO Blogcomments(commentBlogId, commentUserId, commentMessage) VALUES (?, ?, ?)";
            $statement = mysqli_stmt_init($conn);
            $previousLink = "../bloginfo?idBlog=$blogId";
            if (!mysqli_stmt_prepare($statement, $sql)) {
                header("Location: $previousLink&upload=sqlerror");
                exit();
            }
            else {
                mysqli_stmt_bind_param($statement, "iis", $blogId, $userId, $comment);
                mysqli_stmt_execute($statement); 
                header("Location: $previousLink&upload=success");
                exit();
            }
        }
        mysqli_stmt_close($statement);
        mysqli_close($conn);
    }
?>