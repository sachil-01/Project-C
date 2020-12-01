<?php
    include('header.php')
?>

<head>
    <title>Login Form</title>
    <link rel="stylesheet" type="text/css" href="css\LoginStyle.css">
</head>
<body>
    <div class="wrapper">
        <?php
            if (isset($_GET['error'])) {
                if ($_GET['error'] == "unverifiedaccount") {
                    echo '<div class="loginerror"><p>Dit account is nog niet geverifieerd!</p></div>';
                }
                else if ($_GET['error'] == "wrongpass") {
                    echo '<div class="loginerror"><p>E-mail of gebruikersnaam en wachtwoord komen niet overeen.</p></div>';
                }
                else if ($_GET['error'] == "nouser") {
                    echo '<div class="loginerror"><p>E-mail of gebruikersnaam bestaat niet.</p></div>';
                }
            }
        ?>
        <div class="loginbox">
            <!-- Formulier om in te loggen -->
            <?php
            if (isset($_SESSION['userId'])) {
                ?>
                    <h4>U bent al ingelogd!</h4>;
                <?php
            } else {
                ?>
                <form action="includes/login.inc.php" method="post">
                    <br>
                    <h1>Inloggen</h1>
                    <p style="text-align:left">E-mail of Gebruikersnaam</p>
                    <input type="text" name="mailId" placeholder="E-mailadres..." required>
                    <br>
                    <p style="text-align:left">Wachtwoord</p>
                    <input type="password" name="wachtwoord" placeholder="Wachtwoord..." required>
                    <br>
                    <button type="submit" name="login-submit">Login</button>
                    <br><br>
                    <a href="register.php">Registreren</a>
                    <br>
                    <a href="forgotpassword.php">Wachtwoord vergeten?</a>
                </form>
                <?php
            }
            ?>
        </div>
    </div>
</body>

<?php
    include("footer.php");
?>