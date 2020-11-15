<?php
    include('header.php')
?>

<head>
    <title>Register Form</title>
    <link rel="stylesheet" type="text/css" href="css\RegisterStyle.css">
    <link rel="stylesheet" type="text/css" href="css\LoginStyle.css"
</head>
    <!-- Error berichten // later vervangen voor html ingebouwde messages -->
    <div class="wrapper">
        <?php
            if (isset($_GET['error'])) {
                if ($_GET['error'] == "emptyfields") {
                    echo '<div class="registererror"><p>Vul alle velden in!</p></div>';
                }
                else if ($_GET['error'] == "invalidmailuid") {
                    echo '<div class="registererror"><p>Foutieve email en gebruikersnaam</p></div>';
                }
                else if ($_GET['error'] == "invaliduid") {
                    echo '<div class="registererror"><p>Foutieve gebruikersnaam</p></div>';
                }
                else if ($_GET['error'] == "invalidmail") {
                    echo '<div class="registererror"><p>Foutieve email</p></div>';
                }
                else if ($_GET['error'] == "passwordcheck") {
                    echo '<div class="registererror"><p>Uw wachtwoorden komen niet overeen</p></div>';
                }
                else if ($_GET['error'] == "usertaken") {
                    echo '<div class="registererror"><p>Gebruikersnaam is al in gebruik</p></div>';
                }
            }
            else if ($_GET['signup'] == "success") {
                include('PHPMailer/sendmail.php');
            }
        ?>
        <div class="registerbox">
        <form class="registerform" action="includes/register.inc.php" method="post">
            <h1>Signup</h1>
            <div class="registerpart1">
                <p>Gebruikersnaam</p>
                <input type="text" name="uid" placeholder="Gebruikersnaam" required>
                <p>Voornaam</p>
                <input type="text" name="firstName" placeholder="Voornaam" required>
                <p>Achternaam</p>
                <input type="text" name="lastName" placeholder="Achternaam" required>
                <p>Wachtwoord</p>
                <input type="password" name="pwd" placeholder="Wachtwoord" required>
                <p>Herhaal wachtwoord</p>
                <input type="password" name="pwdrepeat" placeholder="Herhaal wachtwoord" required>
            </div>
            <div class="registerpart2">
                <p>E-mail</p>
                <input type="text" name="mail" placeholder="E-mail" required>
                <p>Straatnaam</p>
                <input type="text" name="straatNaam" placeholder="Straatnaam" required>
                <p>Nummer</p>
                <input type="number" name="huisNummer" placeholder="Huisnummer" required>
                <p>Toevoeging</p>
                <input type="text" name="toevoeging" placeholder="Toevoeging">
                <p>Postcode</p>
                <input type="text" name="postcode" placeholder="Postcode" required>
            </div>
            <button type="submit" name="signup-submit">Registreren</button>
            <br><br>
            <a href="loginpagina.php">Inloggen</a>
        </form>
        </div>
        <div class="push"></div>
    </div>
<?php
    include('footer.php')
?>