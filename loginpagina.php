<?php
    include('header.php')
?>



    <div class="container"> 

    <!-- Formulier om in te loggen -->
    <form action="includes/login.inc.php" method="post">
        <input type="text" name="mailId" placeholder="E-mailadres...">
        <input type="password" name="wachtwoord" placeholder="Wachtwoord...">
        <button type="submit" name="login-submit">Login</button>
    </form>
    <a href="register.php">Registreren</a>
    </div>

<?php
    require "footer.php";
?>