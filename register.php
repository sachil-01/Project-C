<?php
    include('header.php')
?>

<head>
    <title>Login Form Design</title>
    <link rel="stylesheet" type="text/css" href="css\RegisterStyle.css">
</head>
    <!-- Error berichten // later vervangen voor html ingebouwde messages -->
    <?php 
        if (isset($_GET['error'])) {
            if ($_GET['error'] == "emptyfields") {
                echo '<p>Vul alle velden in!</p>';
            }
            else if ($_GET['error'] == "invaliduidmail") {
                echo '<p>Foutieve email en gebruikersnaam</p>';
            }
            else if ($_GET['error'] == "invaliduid") {
                echo 'Foutieve gebruikersnaam';
            }
            else if ($_GET['error'] == "invalidmail") {
                echo '<p>Foutieve email</p>';
            }
            else if ($_GET['error'] == "passwordcheck") {
                echo '<p>Uw wachtwoorden komen niet overeen</p>';
            }
            else if ($_GET['error'] == "usertaken") {
                echo '<p>Gebruikersnaam is al in gebruik</p>';
            }
        }
        else if ($_GET['signup'] == "success") {
            echo '<p>Registratie is gelukt !</p>';
        }
    ?>
    <div class="registerbox">
    <form action="includes/register.inc.php" method="post">
        <h1>Signup</h1>
        <p>Gebruikersnaam</p>
        <input type="text" name="uid" placeholder="Gebruikersnaam" required>
        <br>
        <p>E-mail</p>
        <input type="text" name="mail" placeholder="E-mail" required>
        <br>
        <p>Voornaam</p>
        <input type="text" name="firstName" placeholder="Voornaam" required>
        <br>
        <p>Achternaam</p>
        <input type="text" name="lastName" placeholder="Achternaam" required>
        <br>
        <p>Wachtwoord</p>
        <input type="password" name="pwd" placeholder="Wachtwoord" required>
        <br>
        <p>Herhaal wachtwoord</p>
        <input type="password" name="pwdrepeat" placeholder="Herhaal wachtwoord" required>
        <br>
        <button type="submit" name="signup-submit">Registreren</button>
        <br><br>
        <a href="loginpagina.php">Inloggen</a>
    </form>
    </div>
<?php
    include('footer.php')
?>