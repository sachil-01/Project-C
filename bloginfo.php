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

        $sql = "SELECT * FROM Blogpost b JOIN User u ON b.blogUserId = u.idUser LEFT JOIN BlogImage bi ON b.idPost = bi.idBlog WHERE b.idPost = '$blogId'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row

            $row = $result->fetch_assoc();
            //checks if user is the publisher of the blogpost
            if($row["idUser"] == $_SESSION['userId']){
                echo '<div class="userFunctions-btn">
                      <button onclick="showDeletePopUp()" class="user-delete-blogpost-btn">Verwijder</button>
                      <a href="editAdOrBlog.php?blogpostId='.$row["idPost"].'"><button class="user-edit-blogpost-btn">Wijzig</button></a>
                      </div>';
            }
                    echo '<div class="blogInfo">
                            <!-- Slideshow container -->
                            <div class="slideshow-container">';
                                //  Full-width images with number and caption text
                                
                                $resultInner = $conn->query($sql);
                                $currentImage = 1;
                                $totalImages = $resultInner->num_rows;
                                while($row2 = mysqli_fetch_array($resultInner)){
                                    if(empty($row2["imgName"])){
                                        $blogImage = 'images/plantje.png';
                                    } else {
                                        $blogImage = 'uploads/'.$row2['imgName'];
                                    }
                                    echo '<div class="mySlides fade">
                                            <div class="numbertext">'.$currentImage.' / '.$totalImages.'</div>
                                            <img src="'.$blogImage.'">
                                            </div>';
                                            $currentImage++;
                                }
                            
                            if($totalImages > 1){
                            echo'
                                <!-- Next and previous buttons -->
                                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                                <a class="next" onclick="plusSlides(1)">&#10095;</a>
                            </div>
                            <br>
                            
                            <!-- The dots/circles -->
                            <div style="text-align:center">';
                                for($i=1; $i <= $totalImages; $i++)
                                echo '<span class="dot" onclick="currentSlide('.$i.')"></span>';
                            };
                            echo '
                            </div>
                            <br>
                            <h2>'.$row["blogCategory"].'</h2>
                            <h3>'.$row["blogTitle"].'</h3>
                            <p class="blogInfoDesc">'.$row["blogDesc"].'</p>
                      
                            <a href="'.$row["blogLink"].'" class="blogInfoLink" target="_blank">'.$row["blogLink"].'</a>
                            <br>
                            <p class="blogInfoBlogger">Geupload door <a class="userPageRedirection" href="userpage?IdUser='.$row["idUser"].'">'.$row["usernameUser"].'</a> op '.date_format(date_create($row["blogDate"]),"d-m-Y").'</p>
                            <br>
                            <h3>Comment sectie</h3>
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
            // session used for edit page to check if user is the owner of the blogpost
            $_SESSION["idUser"] = $row["idUser"];
            $_SESSION['blogId'] = $row["idPost"];
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
            echo "<div class='newaderror'><p>Blogpost bestaat niet meer.</p></div>";
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
                document.getElementById("userDisplayBlogpost").style.cssText = "height: 75vh;";
            }
        })
    }

    function showDeletePopUp(){
        document.getElementById("userDeleteBlogpostPopUp").style.cssText = "display: block;";
    }

    function userPopUpMessage(chosenOption){
        document.getElementById("userDeleteBlogpostPopUp").style.cssText = "display: none;";
    }

    // image slider
    var slideIndex = 1;
        showSlides(slideIndex);

    // Next/previous controls
    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    // Thumbnail image controls
    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");
    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex-1].style.display = "block";
    dots[slideIndex-1].className += " active";
    }
</script>

<?
    include('footer.php');
    include('feedback.php');
?>