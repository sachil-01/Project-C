<?php
include 'header.php';
?>
<body>
<?php
if (isset($_SESSION['userId'])) {
    // array for saving all file names (time().filename)
    $imageArr = array();
    if(isset($_POST['submit'])){
        //inserts image in uploads folder
        $fileCount = count($_FILES['file']['name']);
        for($i=0; $i < $fileCount; $i++){
            //give filename a time to avoid overwriting a file (unique name)
            $fileName = time().$_FILES['file']['name'][$i];
            move_uploaded_file($_FILES['file']['tmp_name'][$i], 'uploads/'.$fileName);
            //add filename to array
            array_push($imageArr, $fileName);
        }
        // array will be used to insert the filenames (one by one) into the blogImage table (database)
        $_SESSION['images'] = $imageArr;
    }
    //catch error/success messages
    if (isset($_GET['error'])) {
        //shows error message when file extension is not in the allowtypes array
        if ($_GET['error'] == "extension") {
            echo '<div class="newposterror"><p>Ongeldige bestand(en) geupload!</p></div>';
        }
        //shows sql error message
        else if ($_GET['error'] == "sqlerror") {
            echo '<div class="newposterror"><p>Er is iets fout gegaan (sql error).</p></div>';
        }
    }
    else if (isset($_GET['upload'])){
        if ($_GET['upload'] == "success") {
            echo '<div class="newposterror"><p>Uw blogpost is succesvol geupload!</p></div>';
        }
    }
    ?>
    <div class="adform">
        <h2>Nieuwe advertentie</h2><br>
        <form action="includes/newad.inc.php" method="post" enctype="multipart/form-data" target="adpagina.php">
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
            
<!--            Selecteer een foto (max 1MB):-->
<!--            <input type="file" name="files[]" multiple><br><br>-->
            
            <label><label style="color: red;">*</label> = verplicht</label><br><br>
            <input class="newAdButtons" type="submit" name="ad-submit" value="Plaatsen!">
        </form>
        <form action='' method='post' enctype="multipart/form-data">
            <input type='file' name='file[]' id='file' multiple>
            <input class="newAdButtons" type='submit' name='submit' value='upload'>
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
?>