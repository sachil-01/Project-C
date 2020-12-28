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
        <div id="userDisplayBlogpost">
        <?
        require 'includes/dbh.inc.php';
        $blogId = $_GET['idBlog'];

        $sql = "SELECT * FROM Blogpost b JOIN User u ON b.blogUserId = u.idUser JOIN BlogImage bi ON b.idPost = bi.idBlog WHERE b.idPost = '$blogId'";
        $result = $conn->query($sql);
        $number_of_posts = $result->num_rows;
        if ($result->num_rows > 0) {
            // output data of each row

            $row = $result->fetch_assoc();
            //checks if user is the publisher of the blogpost
            if($row["idUser"] == $_SESSION['userId']){
                echo '<div class="userFunctions-btn">
                      <button onclick="showDeletePopUp()" class="user-delete-blogpost-btn">Verwijder</button>
                      <button class="user-edit-blogpost-btn">Wijzig</button>
                      </div>';
            }

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
                    </div>
                    <!-- pop up message when user clicks on delete button -->
                    <div id="userDeleteBlogpostPopUp">
                        <div class="blurBackground-success"></div>

                        <div class="feedback-popup-success">
                            <br>
                            <h1>Weet u zeker dat u uw blogpost wilt verwijderen?</h1>
                            <div class="popup-form">
                                <!-- Close popup form button -->
                                <br>
                                <button class="feedback-submit" id="blogpostDelete" value='.$row["idPost"].' onclick="userDeleteBlogpost(this.value, this.id)">Verwijder blogpost</button>
                                <button class="closefeedback-submit" onclick=userPopUpMessage()>Annuleren</button>
                                <br><br>
                            </div>
                        </div>
                    </div>';
            
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
                <a href="loginpagina">Klik eerst hier om in te loggen voordat u een commentaar kan plaatsen!</a>
            <?php
            }
            ?>
        </form>
    </div>
    <?php
        //Give error when blogpost doesn't exist
        } else {
            echo "Blogpost bestaat niet meer.";
        }
    ?>
    </div>
    </body>
</html>

<!-- change textarea height based on userinput -->
<script>
    function textAreaAdjust(element) {
        element.style.height = "0.5px";
        element.style.height = (25+element.scrollHeight)+"px";
    }

    //blogpostId is the id of the blogpost stored in the button value
    function userDeleteBlogpost(blogpostId, blogpostUser){
        $.ajax({
            url: "adminFunctions.php",
            type: 'post',
            data: {function: "blogpost", id: blogpostId, user: blogpostUser},
            success: function(result)
            {
                //display result after clicking on "delete blogpost"
                document.getElementById("userDisplayBlogpost").innerHTML = result;
            }
        })
    }

    function showDeletePopUp(){
        document.getElementById("userDeleteBlogpostPopUp").style.cssText = "display: block;";
    }

    function userPopUpMessage(chosenOption){
        document.getElementById("userDeleteBlogpostPopUp").style.cssText = "display: none;";
    }
</script>

<?
    include('footer.php');
    include('feedback.php');
?>