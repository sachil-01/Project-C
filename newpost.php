<?php
    include('header.php');
?>
<head>
    <title>Blogpost form</title>
    <link rel="stylesheet" type="text/css" href="css\NewPostStyle.css">
</head>
<body>
    <?php
    if (isset($_SESSION['userId'])) {
    ?>
    <div class="blogpostform">
        <h2>Nieuwe blogpost</h2><br>
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

            <label>Afbeeldingen toevoegen</label><br>
            <input type="file" name="bImage" id="bImage"><br><br>

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