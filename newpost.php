<?php
    session_start();
    include('header.php');
?>
<head>
    <title>Blogpost form</title>
    <link rel="stylesheet" type="text/css" href="css\NewPostStyle.css">
</head>
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
    <div class="blogpostform">
        <h2>Nieuwe blogpost</h2><br>
        <form action='' method='post' enctype="multipart/form-data">
            <input type='file' name='file[]' id='file' multiple>
            <input class="newPostButton" type='submit' name='submit' value='upload'>
        </form>
        <form action="includes/newpost.inc.php" method="post">
            <label>Blogtitel</label><br>
            <input type="text" id="bname" name="bname" required><br><br>
            
            <label>Blogcategorie</label><br>
            <select name="bcategory" id="bcategory">
                <option value="verzorging">Verzorging</option>
                <option value="speciale evenementen">Speciale evenementen</option>
                <option value="vieringen en feestdagen">Vieringen en feestdagen</option>
            </select><br><br>
            
            <label>Beschrijving</label><br>
            <textarea id="bdesc" name="bdesc" required></textarea><br><br>

            <label>URL toevoegen</label><br>
            <input type="url" name="bLink" id="bLink"><br><br>
            <input class="newPostButton" type="submit" name="blog-submit" value="Blogpost plaatsen">
        </form>
    </div>
    <?php
    } else {
        echo'<div class="notloggedin">
                <h4>Om een blogpost te kunnen plaatsen moet u eerst ingelogd zijn. Klik <a href="loginpagina">HIER</a> om in te loggen.</h4>
            </div>';
    }
    ?>
</body>

<?php
    include('footer.php');
?>