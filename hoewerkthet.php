<?php
    include('header.php');
?>

<html>
    <head>
        <title>Hoe werkt het?</title>
        <link rel="stylesheet" type="text/css" href="css\Hoewerkthet.css">
    <style>
        .onsdoel, .plantjes, .blog {
            text-align: center;
        }
        h1{
            margin: 50px auto 0 auto;
        }
        p {
            margin: 30px auto;
            padding: 0 20%;
            font-size: 1.2rem;
        }
        .wallpaper img {
            object-fit: cover;
            width: 100%;
            height: 300px;
            margin-bottom: 100px;
        }
    </style>
    </head>
            <!-- Section One -->
    <body>
        <div class="onsdoel">
            <h1>Ons doel</h1>
            <p>
                FLEURTOP IS EEN ALGEMENE PLANTENWEBSITE WAAR PLANTENLIEFHEBBERS GEZAMELIJK PLANTEN,
                ZADEN EN STEKKEN KUNNEN RUILEN. WIJ STREVEN NAAR EEN LEUKE COMMUNITY WAAR MENSEN HUN ERVARINGEN KUNNEN DELEN,
                PLANTJES KUNNEN RUILEN EN VAN ELKAAR KUNNEN LEREN.
            </p>
            <p>
                HET VERHOGEN VAN DE BIODIVERSITEIT EN
                EEN LEUKE EN LEERZAME COMMUNITY CREËREN IS HEEL BELANGRIJK VOOR ONS
            </p>

        </div> 
        <div class="wallpaper">
            <img src="images\plant-wallpaper1.jpg" alt="">
        </div>

        <div class="plantjes">
            <h1>Plantjes ruilen</h1>
            <p>
                Onze website maakt het voor leden mogelijk om diverse plantjes, stekjes en zaden te ruilen met plan liefhebbers. Je kunt ook zoeken naar een bepaalde soort in de zoekbalk. Voor het plaatsen van een plant dien je de soort
te specificeren, een naam te geven, de hoeveelheid water, een foto en een optionele beschrijving. Nadat je op een 
post klikt kan er een mailuitwisseling gestart worden om de ruil voort te zetten. Zodra een ruil afgerond is mag je ook een beoordeling plaatsen. 
            </p>
        </div> 
        <div class="wallpaper">
            <img src="images\plant-wallpaper1.jpg" alt="">
        </div>

        <div class="blog">
            <h1>Plogpost Pagina</h1>
            <p>
            Wij houden van een leuke sfeer in de community, daarom hebben wij een plek gemaakt om leuke ervaringen met onze plantliefhebbers te delen. 
            Heb jij een leuke foto van een plant, of wil je iets interessants delen? Post het in onze blogpost pagina! Talloze andere leden kunnen dit zien en mogen ook reageren.
            Wij hopen zo om  de community actief en enthousiast te houden voor lange gebruikers en nieuwkomers. 
            </p>
        </div> 
        <div class="wallpaper">
            <img src="images\plant-wallpaper1.jpg" alt="">
        </div>



    </body>
</html>

<?php 
    include('footer.php');
    include('feedback.php');
?>