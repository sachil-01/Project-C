<?php
    session_start();
    include('header.php');
?>
<html>
    <head>
        <title>Comment section</title>
        <link rel="stylesheet" type="text/css" href="css\BlogStyle.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.3/tiny-slider.css">
    </head>

    <body>
    <?
        require 'includes/dbh.inc.php';
        $blogId = $_GET['idBlog'];

        $sql = "SELECT * FROM Blogpost b JOIN User u ON b.blogUserId = u.idUser JOIN BlogImage bi ON b.idPost = bi.idBlog WHERE b.idPost = '$blogId'";
        $result = $conn->query($sql);
        $number_of_posts = $result->num_rows;
        if ($result->num_rows > 0) {
            // output data of each row

            $row = $result->fetch_assoc();
            echo'<div class="advWrapper">
                        <div class="slidertns">';
                        $resultInner = $conn->query($sql);
                            while ($row2 = mysqli_fetch_array($resultInner)) {

                                echo ' <img src="uploads/'.$row2["imgName"].'" alt="">';

                            }
                   echo' </div>
                        <div class="plantInfo">
                            <div class="plantInfoMargin">
                                <p>'.date_format(date_create($row["blogDate"]),"d-m-Y").'</p>
                                <h3>Geupload door:</h3>
                                <p>'.$row["usernameUser"].'</p>
                            </div>
                        </div>

                        <div class="plantDescription">
                            <h2>'.$row["blogTitle"].'</h2>
                            <h3>'.$row["blogCategory"].'</h3>
                            <h3 class="plantDesc">Beschrijving</h3>
                            <p>'.$row["blogDesc"].'</p>
                        </div>
                        <div class="moreAds">
                            <h3>Meer van '.$row["usernameUser"].'</h3>
                            <div class="moreAdsImg">
                                <img src="uploads/'.$row["imgName"].'" alt="">
                                <img src="uploads/'.$row["imgName"].'" alt="">
                                <img src="uploads/'.$row["imgName"].'" alt="">
                            </div>
                        </div>
                    </div>';

        } else {
            echo "0 results";
        }
        // $conn->close();

            
        if (isset($_GET['idBlog'])) {
            $_SESSION['blogId'] = $_GET['idBlog'];
        }
    ?>
    <div class="comment-section">
        <?php
            // Load chat history

            $sql = "SELECT u.idUser, u.usernameUser, bc.commentDate, bc.commentMessage, commentId
                    FROM Blogcomments bc
                    JOIN User u ON bc.commentUserId = u.idUser
                    WHERE bc.commentBlogId = '$blogId'
                    ORDER BY bc.commentId";
            $statement = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($statement, $sql)) {
                echo '<div class="newposterror"><p>Er is iets fout gegaan (sql error).</p></div>';
            }
            else {
                mysqli_stmt_execute($statement);
                $result = mysqli_stmt_get_result($statement);
                foreach ($result as $comment) {
                    $name = $comment['usernameUser'];
                    $date = date("H:i d-m-Y", strtotime($comment['commentDate']));
                    $message =  $comment['commentMessage'];

                    if($comment['idUser'] == $_SESSION['userId']){
                        $myComment = "style='float: right; color: white; background-color: #1B701B; border-color:;'";
                    } else {
                        $myComment = "";
                    }
                    echo "<div class='comment-bubble style='$myComment'>
                            <h1>$name</h1><h2>$date</h2>
                            <br>
                            <p>$message</p>
                          </div>";
                }
            }
        ?>
        <!-- post comment form -->
        <form class="comment-upload" action="includes/uploadComment.inc.php" method="post">
            <?php
            // checks if user is logged in, else display button for user to log in
            if(isset($_SESSION['userId'])) {
            ?>
                <textarea onkeyup="textAreaAdjust(this)" type="text" name="comment-input" placeholder="Klik hier om een comment toe te voegen..." required></textarea>
                <button name="comment-submit">Plaats comment</button>
            <?php
            } else {
            ?>
                <a href="loginpagina" >Klik eerst hier om in te loggen voordat u een commentaar kan plaatsen!</a>
            <?php
            }
            ?>
        </form>
    </div>
    </body>
</html>

<!-- change textarea height based on userinput -->
<script>
    function textAreaAdjust(element) {
        element.style.height = "0.5px";
        element.style.height = (25+element.scrollHeight)+"px";
    }
</script>




<?
    include('footer.php');
    include('feedback.php');
?>