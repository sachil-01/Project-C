<?php
    include('header.php')
?>

<head>
    <title>Login Form</title>
    <link rel="stylesheet" type="text/css" href="css\LoginStyle.css">
</head>
<body>
    <?php
        if (isset($_GET['error'])) {
            if ($_GET['error'] == "wrongemail") {
                echo '<div class="loginerror"><p>Het opgegeven e-mailadres bestaat niet!</p></div>';
            }
            else if ($_GET['error'] == "sqlerror") {
                echo '<div class="loginerror"><p>Er is iets foutgegaan (sqlerror)</p></div>';
            }
        } else if (isset($_GET['success'])) {
            if ($_GET['success'] == "send") {
                echo '<div class="loginerror"><p>We hebben een bevestigingsmail naar het opgegeven adres verzonden!</p></div>';
            }
        //checks if website should show the reset password form or the normal form for sending a reset password mail
        } else if(isset($_GET['setpassword'])){
            require 'includes/dbh.inc.php';

            // Include decryption function
            include('encrypt_decrypt.php');

            //proces verification
            $resetpassword = $_GET['setpassword'];
            $key = $_GET['key'];

            // Decrypt username with length of username as key
            $decrypted_txt = encrypt_decrypt('decrypt', $resetpassword, $key);

            // Checks if given decrypted text (mail) exists in database else send error message
            $sql = "SELECT emailUser, usernameUser FROM User WHERE emailUser = '$decrypted_txt'";
            $statement = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($statement, $sql)) {
                echo '<div class="loginerror"><p>Er is iets foutgegaan (sqlerror)</p></div>';
            }
            else {
                mysqli_stmt_execute($statement);
                $result = mysqli_stmt_get_result($statement);
                $row = mysqli_fetch_assoc($result);
                $username = $row['usernameUser'];
                //if email not found in database, give error message
                if (empty($row['emailUser'])) {
                    echo '<div class="loginerror"><p>Het opgegeven e-mailadres bestaat niet meer!</p></div>';
                }
                //if email is found in database, check when user clicks on submit button
                else{
                    if (isset($_POST['password-submit'])){
                        //checks if given password and repeat password are the same input
                        if($_POST['newPassword'] !== $_POST['repeatNewPassword']){
                            echo '<div class="loginerror"><p>Wachtwoorden komen niet overeen!</p></div>';
                        } else {
                            //hash password
                            $hashedPwd = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
                            $sql = "UPDATE User SET passUser = '$hashedPwd' WHERE emailUser = '$decrypted_txt' LIMIT 1";
                            $statement = mysqli_stmt_init($conn);
                            if (!mysqli_stmt_prepare($statement, $sql)) {
                                echo '<div class="loginerror"><p>Er is iets foutgegaan (sqlerror)</p></div>';
                            }
                            else {
                                //update password in database
                                mysqli_stmt_execute($statement);
                                if(!isset($_SESSION['userId'])){
                                    echo "<script type='text/javascript'> document.location = 'loginpagina?success=updatesuccess'; </script>";
                                } else {
                                    echo "<script type='text/javascript'> document.location = 'profilepage?success=updatesuccess'; </script>";
                                }
                            }
                        }
                    }
                    ?>
                        <div class="wrapper">
                            <div class="loginbox">
                                <form action="" method="post">
                                    <br>
                                    <h1>Wachtwoord wijzigen</h1>
                                    <p style="text-align:left">Nieuwe wachtwoord</p>
                                    <input type="password" name="newPassword" minlength="10" placeholder="Nieuwe wachtwoord..." required>
                                    <br>
                                    <p style="text-align:left">Herhaal nieuwe wachtwoord</p>
                                    <input type="password" name="repeatNewPassword" placeholder="Herhaal nieuwe wachtwoord..." minlength="10" required>
                                    <br>
                                    <button type="submit" name="password-submit">Wijzig wachtwoord</button>
                                    <br><br>
                                </form>
                            </div>
                        </div>
                    <?php
                }
            }
        }
    //checks if website should show the reset password form or the normal form for sending a reset password mail
    if(!isset($_GET['setpassword'])){
        ?>
        <div class="wrapper">
            <div class="loginbox">
                <form action="PHPMailer/sendmail.php" method="post">
                    <br>
                    <h1>Wachtwoord opnieuw instellen</h1>
                    <p style="text-align:left">E-mail</p>
                    <input type="text" name="mailId" placeholder="E-mailadres..." required>
                    <br>
                    <button type="submit" name="mail-submit">Stuur een bevestigingsmail</button>
                    <br><br>
                    <a href="loginpagina.php">Inloggen</a>
                </form>
            </div>
        </div>
        <?php
    }
    ?>
</body>

<?php
    include('footer.php');
    include('feedback.php');
?>