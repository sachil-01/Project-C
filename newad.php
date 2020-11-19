<?php
include 'header.php';
?>
<body>
<?php
if (isset($_SESSION['userId'])) {
    echo '
    <div class="adform">
        <h2>Nieuwe advertentie</h2><br>
        <form action="newad.inc.php" method="post" enctype="multipart/form-data" target="adpagina">
            <label for="pname">Plantnaam:</label><br>
            <input type="text" id="pname" name="pname"><br><br>
            
            <label for="psoort">Plantensoort:</label><br>
            <input type="text" id="psoort" name="psoort"><br><br>
            
            <label>Type advertentie:</label><br>
            <input type="radio" id="stekje" name="type" value="stekje">
            <label for="stekje">Stekje</label><br>
            <input type="radio" id="zaad" name="type" value="zaad">
            <label for="zaad">Zaad</label><br>
            <input type="radio" id="kiemplant" name="type" value="kiemplant">
            <label for="kiemplant">Kiemplant</label><br>
            <input type="radio" id="none" name="type" value="none">
            <label for="none">Weet ik niet</label><br><br>
            
            <label>Hoeveelheid water nodig:</label><br>
            <label>
                <input style="input[type=radio] {
                                position: absolute;
                                opacity: 0;
                                width: 0;
                                height: 0;
                            }

                            /* IMAGE STYLES */
                            input[type=radio] + img {
                                cursor: pointer;
                            }
                            
                            /* CHECKED STYLES */
                            input[type=radio]:checked + img {
                                outline: 2px solid #31950f;
                        }" type="radio" id="weinig" name="water" value="1">
                <img src="images/weinigwater.png">
            </label>
            
            <label>
                <input type="radio" id="gemiddeld" name="water" value="2">
                <img src="images/gemiddeldwater.png">
            </label>   
                     
            <label>
                <input type="radio" id="veel" name="water" value="3">
                <img  src="images/veelwater.png">
            </label>
            
            <label>
                <input type="radio" id="none" name="water" value="0">
                <img  src="images/weetniet.png">
            </label>
            <br><br>
            
            <label>Hoeveelheid licht nodig:</label><br>
            <label>
                <input type="radio" id="weinig" name="licht" value="1">
                <img src="images/weinigwater.png">
            </label>
            
            <label>
                <input type="radio" id="gemiddeld" name="licht" value="2">
                <img src="images/gemiddeldwater.png">
            </label>   
                     
            <label>
                <input type="radio" id="veel" name="licht" value="3">
                <img src="images/veelwater.png">
            </label>
            
            <label>
                <input type="radio" id="none" name="licht" value="0">
                <img src="images/weetniet.png">
            </label>
            
            <br><label for="desc">Beschrijving</label><br>
            <textarea id="desc" name="desc" rows="5" cols="50"></textarea><br>
            Selecteer een foto (max 1MB):
            <input type="file" name="fileToUpload" id="fileToUpload"><br><br>
            <input class="newAdButtons" type="submit" value="Plaatsen!">
        </form>


    </div>
    ';
} else {
    echo '<div class="notloggedin">
            <h4>Om een advertentie te kunnen plaatsen moet u eerst ingelogd zijn. Klik <a href="loginpagina">HIER</a> om in te loggen.</h4>
          </div>';
}
?>

</body>