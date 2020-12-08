<?php
    session_start();
    include('header.php');
?>

<head>
    <title>Advertisements</title>
    <link rel="stylesheet" type="text/css" href="css\adPagina.css">
</head>

<body>

    

    <div class="gallery">
        <h1>Alle aanbiedingen</h1>
        <a class="newadknop" href="newad"><i class="fas fa-plus"></i>Plant plaatsen</a>
        
        <!-- <form method="POST">
        <input type="text" name="search">
        <input type="submit" name="submit"> 
        </form> -->
        
        
        
        <div class="searchbar-div">
            <div class="searchbar-margin">
                <div class="searchbar-main">
                    <div class="searchbar-main-content">
                        <input type="search" class="searchbar-input" onfocus="this.value=''" placeholder="Zoeken...">
                        <!-- <button type="submit" class="zoekknop">Zoeken</button> -->
                    </div>
                </div>
            </div>
        </div>

        

        
    </div> 
</body>

<div class="img-area">
<?php 

require 'includes/dbh.inc.php';

$sql = "SELECT plantName, plantLatinName, postDate FROM Advertisement";

$statement = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($statement, $sql)) {
    header("Location: adpagina.php?error=sqlerror");
    echo '<div class="newposterror"><p>Er is iets fout gegaan (sql error).</p></div>';
}
else {
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    if ($row = mysqli_fetch_assoc($result)) {
        foreach ($result as $adv) {
            $idAd = $adv['idAd'];
            $plantName = $adv['plantName'];
            $plantLatinName = $adv['plantLatinName'];
            $plantCategory = $adv['plantCategory'];
            $plantDesc = $adv['plantDesc'];
            $postDate = $adv['postDate'];
            $waterManage = $adv['waterManage'];
            $lightPattern = $adv['lightPattern'];
            $userId = $adv['userId'];
            $adDate = date("d-m-Y", strtotime($postDate));
            
echo '<div class="plant">
<div class="adImage">
    <img src="images/plant1.jpg" alt="">
</div>
<div class="description">
    <h2>'.$plantName.'</h2>
    <br>
    <h3> Afstand: <span>0km</span></h3>
    <h3> Datum: <span>'.$adDate.'</span></h3>
</div>
</div>';

        }
    }
};
?>


</div>

<?php
    include('footer.php');
    include('feedback.php');
?>
