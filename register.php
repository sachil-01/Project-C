<?php
    include('header.php')
?>

<head>
    <title>Register Form</title>
    <link rel="stylesheet" type="text/css" href="css\RegisterStyle.css">
    <link rel="stylesheet" type="text/css" href="css\LoginStyle.css">
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
                else if ($_GET['error'] == "emailtaken") {
                    echo '<div class="registererror"><p>Emailadres is al in gebruik</p></div>';
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
                    <p class="required">Gebruikersnaam</p>
                    <input type="text" name="uid" placeholder="Gebruikersnaam" required>
                    <p class="required">Voornaam</p>
                    <input type="text" name="firstName" placeholder="Voornaam" required oninvalid="this.setCustomValidity('Alleen alfabetische letters zijn toegestaan')"
       oninput="this.setCustomValidity('')" pattern="[A-Za-z\s]{1,}">
                    <p class="required">Achternaam</p>
                    <input type="text" name="lastName"  placeholder="Achternaam" required oninvalid="this.setCustomValidity('Alleen alfabetische letters zijn toegestaan')"
       oninput="this.setCustomValidity('')" pattern="[A-Za-z\s]{1,}">
                    <p class="required">Wachtwoord</p>
                    <input type="password" name="pwd" placeholder="Wachtwoord"  minlength="10" required>
                    <p class="required">Herhaal wachtwoord</p>
                    <input type="password" name="pwdrepeat" placeholder="Herhaal wachtwoord" minlength="10" required>
                </div>
                <div class="registerpart2">
                    <p class="required">E-mail</p>
                    <input type="text" name="mail" placeholder="E-mail" required>
                    <p class="required">Straatnaam</p>
                    <input type="text" name="straatNaam"  placeholder="Straatnaam" required oninvalid="this.setCustomValidity('Alleen alfabetische letters zijn toegestaan')"
       oninput="this.setCustomValidity('')" pattern="[A-Za-z\s]{1,}">
                    <p class="required">Huisnummer</p>
                    <input type="number" name="huisNummer" min="1" max="18926"  placeholder="Huisnummer" required>
                    <p>Toevoeging</p>
                    <input type="text" name="toevoeging" placeholder="Toevoeging" pattern="[A-Za-z]{1,}" oninvalid="this.setCustomValidity('Alleen alfabetische letters zijn toegestaan')"
       oninput="this.setCustomValidity('')" pattern="[A-Za-z\s]{1,}">
                    <p class="required">Postcode</p>
                    <input type="text" name="postcode" pattern="[0-9]{4}[A-Za-z]{2}" placeholder="Postcode" maxlength="6" required  oninvalid="this.setCustomValidity('Uw postcode moet 4 cijfers en 2 letters bevatten voorbeeld: 123AB')"
       oninput="this.setCustomValidity('')" >
                </div>
                <button type="submit" name="signup-submit">Registreren</button>
                <br><br>
                <a href="loginpagina.php">Inloggen</a>
            </form>
        </div>
        <div class="push"></div>
    </div>
<?php
    include('footer.php');
    include('feedback.php');
?>