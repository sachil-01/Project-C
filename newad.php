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
                $imageSize = true;
                $imageFormats = true;
                if(!in_array(strtolower(pathinfo($_FILES['file']['name'][$i], PATHINFO_EXTENSION)), $allowed)){
                    $imageFormats = false;
                    break;
                } else if ($_FILES['file']['size'][$i] > 4*1048576){ //4*1048576 == 4mb
                    $imageSize = false;
                    break;
                }
            }
            
            if($imageFormats && $imageSize){
                if($fileCount >= 1 && $fileCount <= 3){
                    $plantname = $_POST["pname"];
                    $plantname = $_POST["pname"];
                    str_replace($plantname,'<', '');
                    str_replace($plantname,'>', '');
                    $plantlatinname = $_POST["plname"];
                    str_replace($plantlatinname,'<', '');
                    str_replace($plantlatinname,'<', '');
                    $plantsoort = $_POST["psoort"];

                    $plantcategory = $_POST["type"];
                    $desc = $_POST["desc"];
                    str_replace($desc,'<', '');
                    str_replace($desc,'<', '');
                    
                    $water = $_POST["water"];
                    $light = $_POST["licht"];
                    $userId = $_SESSION['userId'];

                    // Insert blogpost data into database
                    $sql = "INSERT INTO Advertisement(plantName, plantLatinName, plantType, plantCategory, plantDesc, waterManage, lightPattern, Userid) VALUES(?,?,?,?,?,?,?,?)";
                    $statement = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($statement, $sql)) {
                        header("Location: newad.php?error=sqlerror");
                        echo '<div class="newaderror"><p>Er is iets fout gegaan (sql error: 101).</p></div>';
                    }
                    else {
                        mysqli_stmt_bind_param($statement, "sssssiii", $plantname, $plantlatinname, $plantsoort, $plantcategory, $desc, $water, $light, $userId);
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
            } else if($imageFormats == false){
                echo '<div class="newaderror"><p>Alleen "jpg", "png", "gif" en "jpeg" bestanden zijn toegestaan!</p></div>';
            } else if($imageSize == false){
                echo '<div class="newaderror"><p>Uw afbeelding mag maar maximaal 4mb zijn!</p></div>';
            }
        }
        ?>
        <div class="adform">
            <h2>Nieuwe advertentie</h2><br>
            <form action="" method="post" enctype="multipart/form-data" target="_self">
                <label for="pname">Plantnaam <label style="color: red;">*</label></label><br>
                <input type="text" id="pname" name="pname" required><br><br>

                <label for="plname">Latijnse naam</label><br>
                <input type="text" id="plname" name="plname"><br><br>

                <label for="psoort">Soort <label style="color: red;">*</label></label><br>
                <select  id="psoort" name="psoort">
                    <option value="boom">boom</option>
                    <option value="struik">struik</option>
                    <option value="kruidachtige">kruidachtige</option>
                    <option value="bodembedekker">bodembedekker</option>
                    <option value="klimplant">klimplant</option>
                    <option value="waterplant">waterplant</option>
                </select><br><br>

                <label>Type <label style="color: red;">*</label></label><br>
                <input type="radio" id="stekje" name="type" value="stekje" required>
                <label for="stekje">Stekje</label><br>
                <input type="radio" id="zaad" name="type" value="zaad">
                <label for="zaad">Zaad</label><br>
                <input type="radio" id="kiemplant" name="type" value="kiemplant">
                <label for="kiemplant">Plant</label><br>
                <input type="radio" id="bol" name="type" value="bol">
                <label for="bol">Bollen</label><br>
                <input type="radio" id="none" name="type" value="onbekend">
                <label for="none">Weet ik niet</label><br>
                
                <br><label for="desc">Beschrijving <label style="color: red;">*</label></label><br>
                <textarea id="desc" name="desc" rows="5" cols="50" required></textarea><br><br>
                
                <label>Hoeveelheid water nodig <label style="color: red;">*</label></label><br>
                <label>
                    <input style="position: absolute; opacity: 0; width: 0; height: 0; cursor: pointer;" type="radio" id="weinig" name="water" value="1" required>
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
                
                <label>Hoeveelheid licht nodig <label style="color: red;">*</label></label><br>
                <label>
                    <input style="position: absolute; opacity: 0; width: 0; height: 0; cursor: pointer;" type="radio" id="weinig" name="licht" value="1" required>
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
                
                <label>Afbeeldingen <label style="color: red;">*</label></label><br>
                <!-- display image after selecting -->
                <div id="imagePreviewGallery">
                    <img id="imagePreview">
                    <button type="button" id="imagePreviewPrevious" onclick="previewCurrentImage('previous')" class="newAdButtons"><span>Vorige afbeelding</span></button>
                    <button type="button" id="imagePreviewNext" onclick="previewCurrentImage('next')" class="newAdButtons"><span>Volgende afbeelding</span></button>
                    <br><br><br>
                </div>
                <label class="uploaddescription">Selecteer een foto (max 4MB)</label><br>
                <input type="file" name="file[]" id="file" accept=".png, .jpg, .jpeg, .gif" onchange="fileFunctions()" multiple><br><br>
                
                <label><label style="color: red;">*</label> = verplicht</label><br><br>
                <p id="maxImageWarning" style="color: red;">Uw advertentie mag maximaal 3 afbeelding bevatten.</p>
                <input class="newAdButtons" type="submit" name="ad-submit" value="Plaatsen!" id="ad-submit">
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

<script>
    imageWarning();

    function fileFunctions(){
        previewImage();
        imageWarning();
    }
    //checks if advertisement contains at least 1 picture
    function imageWarning(){
        var file = document.getElementById("file").files;
        //disable update button if advertisement has more than 3 images
        if(file.length > 3){
            document.getElementById('ad-submit').disabled = true;
            document.getElementById('maxImageWarning').hidden = false;
        } else {
            document.getElementById('ad-submit').disabled = false;
            document.getElementById('maxImageWarning').hidden = true;
        }
    }
</script>

<?php
    include('footer.php');
    include('feedback.php');
?>