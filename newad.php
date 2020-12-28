<?php
session_start();
include('header.php');
?>
<head>
    <title>Advertisement form</title>
    <link rel="stylesheet" type="text/css" href="css\NewAdStyle.css">
</head>
<body>
    <?php
    if (isset($_SESSION['userId'])) {
        if(isset($_POST['ad-submit'])){
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
                if($fileCount >= 1 && $fileCount <= 3){
                    $plantname = $_POST["pname"];
                    $plantlatinname = $_POST["psoort"];
                    $plantcategory = $_POST["type"];
                    $desc = $_POST["desc"];
                    $water = $_POST["water"];
                    $light = $_POST["licht"];
                    $userId = $_SESSION['userId'];

                    // Insert blogpost data into database
                    $sql = "INSERT INTO Advertisement(plantName, plantLatinName, plantCategory, plantDesc, waterManage, lightPattern, Userid) VALUES(?,?,?,?,?,?,?)";
                    $statement = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($statement, $sql)) {
                        header("Location: newad.php?error=sqlerror");
                        echo '<div class="newaderror"><p>Er is iets fout gegaan (sql error: 101).</p></div>';
                    }
                    else {
                        mysqli_stmt_bind_param($statement, "ssssiii", $plantname, $plantlatinname, $plantcategory, $desc, $water, $light, $userId);
                        mysqli_stmt_execute($statement);
                    }

                    // Retrieve advertisement ID before inserting advertisement image(s) to database
                    $sql = "SELECT idAd FROM Advertisement WHERE userId = $userId ORDER BY postDate DESC LIMIT 1";
                    $statement = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($statement, $sql)) {
                        header("Location: newad.php?error=sqlerror");
                        echo '<div class="newaderror"><p>Er is iets fout gegaan (sql error: 102).</p></div>';
                    }
                    else {
                        mysqli_stmt_execute($statement);
                        $result = mysqli_stmt_get_result($statement);
                        if ($row = mysqli_fetch_assoc($result)) {
                            $idAdvert= $row['idAd'];
                        }
                    }

                    //inserts image in uploads folder
                    for($i=0; $i < $fileCount; $i++){
                        //checks if there is a file uploaded
                        if(UPLOAD_ERR_OK == $_FILES['file']['error'][$i]){
                            //give filename a time to avoid overwriting a file (unique name)
                            $fileName = time().$_FILES['file']['name'][$i];

                            //insert image to database
                            $sql = "INSERT INTO AdImage(imgName, idAdvert) VALUES (?, ?)";
                            $statement = mysqli_stmt_init($conn);
                            if (!mysqli_stmt_prepare($statement, $sql)) {
                                header("Location: newad.php?error=sqlerror");
                                echo '<div class="newaderror"><p>Er is iets fout gegaan (sql error: 103).</p></div>';
                            }
                            else {
                                mysqli_stmt_bind_param($statement, "ss", pathinfo($fileName, PATHINFO_BASENAME), $idAdvert);
                                mysqli_stmt_execute($statement);

                                //insert image to uploads folder
                                move_uploaded_file($_FILES['file']['tmp_name'][$i], 'uploads/'.$fileName);
                            }
                        }
                    }
                    header("Location: newad.php?upload=success");
                    echo '<div class="newaderror"><p>Uw advertentie is succesvol geupload!</p></div>';
                } else {
                    echo '<div class="newaderror"><p>Er is een minimum van 1 foto en een maximum van 3 foto\'s toegestaan.</p></div>';
                }
            } else if($fileCount == 1){
                echo '<div class="newaderror"><p>U moet minimaal 1 foto uploaden! Er is een maximum van 3 foto\'s toegestaan.</p></div>';
            } else {
                echo '<div class="newaderror"><p>Alleen "jpg", "png", "gif" en "jpeg" bestanden zijn toegestaan!</p></div>';
            }
        }
        ?>
        <div class="adform">
            <h2>Nieuwe advertentie</h2><br>
            <form action="" method="post" enctype="multipart/form-data" target="adpagina">
                <label for="pname">Plantnaam:<label style="color: red;">*</label></label><br>
                <input type="text" id="pname" name="pname" required><br><br>
                
                <label for="psoort">Plantensoort:</label><br>
                <input type="text" id="psoort" name="psoort"><br><br>
                
                <label>Type advertentie:<label style="color: red;">*</label></label><br>
                <input type="radio" id="stekje" name="type" value="stekje">
                <label for="stekje">Stekje</label><br>
                <input type="radio" id="zaad" name="type" value="zaad">
                <label for="zaad">Zaad</label><br>
                <input type="radio" id="kiemplant" name="type" value="kiemplant">
                <label for="kiemplant">Kiemplant</label><br>
                <input type="radio" id="none" name="type" value="none">
                <label for="none">Weet ik niet</label><br><br>
                
                <br><label for="desc">Beschrijving<label style="color: red;">*</label></label><br>
                <textarea id="desc" name="desc" rows="5" cols="50" required></textarea><br><br>
                
                <label>Hoeveelheid water nodig:<label style="color: red;">*</label></label><br>
                <label>
                    <input style="position: absolute; opacity: 0; width: 0; height: 0; cursor: pointer;" type="radio" id="weinig" name="water" value="1">
                    <img style="cursor: pointer;" src="images/weinigwater.png">
                </label>
                
                <label>
                    <input style="position: absolute; opacity: 0; width: 0; height: 0; cursor: pointer;" type="radio" id="gemiddeld" name="water" value="2">
                    <img style="cursor: pointer;" src="images/gemiddeldwater.png">
                </label>   
                        
                <label>
                    <input style="position: absolute; opacity: 0; width: 0; height: 0; cursor: pointer;" type="radio" id="veel" name="water" value="3">
                    <img style="cursor: pointer;" src="images/veelwater.png">
                </label>
                
                <label>
                    <input style="position: absolute; opacity: 0; width: 0; height: 0; cursor: pointer;" type="radio" id="none" name="water" value="0">
                    <img style="cursor: pointer;" src="images/weetniet.png">
                </label>
                <br><br>
                
                <label>Hoeveelheid licht nodig:<label style="color: red;">*</label></label><br>
                <label>
                    <input style="position: absolute; opacity: 0; width: 0; height: 0; cursor: pointer;" type="radio" id="weinig" name="licht" value="1">
                    <img style="cursor: pointer;" src="images/weiniglicht.png">
                </label>
                
                <label>
                    <input style="position: absolute; opacity: 0; width: 0; height: 0; cursor: pointer;" type="radio" id="gemiddeld" name="licht" value="2">
                    <img style="cursor: pointer;" src="images/gemiddeldlicht.png">
                </label>   
                        
                <label>
                    <input style="position: absolute; opacity: 0; width: 0; height: 0; cursor: pointer;" type="radio" id="veel" name="licht" value="3">
                    <img style="cursor: pointer;" src="images/veellicht.png">
                </label>
                
                <label>
                    <input style="position: absolute; opacity: 0; width: 0; height: 0; cursor: pointer;" type="radio" id="none" name="licht" value="0">
                    <img style="cursor: pointer;" src="images/weetniet.png">
                </label>
                <br><br>
                
                <label>Selecteer een foto (max 1MB):</label><br>
                <input type="file" name="file[]" id="file" multiple><br><br>
                
                <label><label style="color: red;">*</label> = verplicht</label><br><br>
                <input class="newAdButtons" type="submit" name="ad-submit" value="Plaatsen!">
            </form>


        </div>
    <?php
    } else {
        echo '<div class="notloggedin">
                <h4>Om een advertentie te kunnen plaatsen moet u eerst ingelogd zijn. Klik <a href="loginpagina">HIER</a> om in te loggen.</h4>
            </div>';
    }
    ?>
</body>

<?php
    include('footer.php');
    include('feedback.php');
?>