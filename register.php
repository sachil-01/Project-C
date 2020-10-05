<?php
    include('header.php')
?>

    <h1>Signup</h1>
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
    <form action="includes/register.inc.php" method="post">
        <input type="text" name="uid" placeholder="Username">
        <input type="text" name="mail" placeholder="E-mail">
        <input type="password" name="pwd" placeholder="Wachtwoord">
        <input type="password" name="pwdrepeat" placeholder="Herhaal wachtwoord">
        <button type="submit" name="signup-submit">Registreren</button>

    </form>

<?php
    include('footer.php')
?>