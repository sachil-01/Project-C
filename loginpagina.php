<?php
    include('header.php')
?>

<head>
    <title>Login Form</title>
    <link rel="stylesheet" type="text/css" href="css\LoginStyle.css">
</head>
<body>
    <div class="loginbox" style="text-align:center"> 
        <!-- Formulier om in te loggen -->
        <form action="includes/login.inc.php" method="post">
            <br>
            <h1>Inloggen</h1>
            <p style="text-align:left">E-mail</p>
            <input type="text" name="mailId" placeholder="E-mailadres...">
            <!-- <br> zorgt dat de input velden onder elkaar staan-->
            <br>
            <p style="text-align:left">Wachtwoord</p>
            <input type="password" name="wachtwoord" placeholder="Wachtwoord...">
            <br>
            <button type="submit" name="login-submit">Login</button>
            <br><br>
            <a href="register.php">Registreren</a>
        </form>
    </div>
</body>

<?php
    include("footer.php");
?>