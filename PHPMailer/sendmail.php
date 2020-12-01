<?php
    session_start();
    // Send email
    // Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    // Load Composer's autoloader
    require 'vendor/autoload.php';

    // Include encryption function
    include('../encrypt_decrypt.php');

    $mail = new PHPMailer();

    //Server settings
    $mail->SMTPDebug = false;                                           // Enable verbose debug output
    $mail->isSMTP();                                                    // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                               // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                            // Enable SMTP authentication
    $mail->Username   = 'FleurtOpMail2@gmail.com';                       // SMTP username
    $mail->Password   = 'FleurtOpMail222';                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                   // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                               // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    if (isset($_POST['mail-submit'])){
        //Checks if system has to send a "Reset password" mail
        $email = $_POST['mailId'];

        require '../includes/dbh.inc.php';
        // Checks if given mail exists in database else send error message
        $sql = "SELECT emailUser, usernameUser FROM User WHERE emailUser = '$email'";
        $statement = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($statement, $sql)) {
            header("Location: ../forgotpassword.php?error=sqlerror");
            exit();
        }
        else {
            mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);
            $row = mysqli_fetch_assoc($result);
            $username = $row['usernameUser'];
            if (empty($row['emailUser'])) {
                header("Location: ../forgotpassword.php?error=wrongemail");
                exit();
            }
        }
        //Recipients
        $mail->setFrom('FleurtOpMail2@gmail.com', 'Fleurt Op');
        $mail->addAddress("$email", "$username");     // Add a recipient

        // Encrypt email with length of email as key
        $key = strlen($email);
        $encrypted_txt = encrypt_decrypt('encrypt', $email, $key);

        // Content
        $mail->isHTML(true);                                                  // Set email format to HTML
        $mail->Subject = 'Wachtwoord opnieuw instellen';

        $mail->Body = "Beste $username,<br><br>
                    Om uw wachtwoord opnieuw in te stellen kunt u op de onderstaande link klikken.<br>
                    Stel uw wachtwoord opnieuw in: <a href='https://www.roy-van-der-lee.nl/fleurtop/forgotpassword.php?setpassword=$encrypted_txt&key=$key'>Wachtwoord opnieuw instellen</a><br><br>
                    Met vriendelijk groet,<br>
                    Fleurt Op";

        $mail->send();

        header("Location: ../forgotpassword.php?success=send");
        exit();
    }

    $username = $_SESSION['username'];
    $email = $_SESSION['mail'];
    $key = strlen($username);

    // Include encryption function
    include('encrypt_decrypt.php');

    // Encrypt username with length of username as key
    $encrypted_txt = encrypt_decrypt('encrypt', $username, $key);

    //Recipients
    $mail->setFrom('FleurtOpMail2@gmail.com', 'Fleurt Op');
    $mail->addAddress("$email", "$username");     // Add a recipient

    // Content
    $mail->isHTML(true);                                                  // Set email format to HTML
    $mail->Subject = 'Email verificatie';

    $mail->Body = "Beste $username,<br><br>
                Om uw registratie te voltooien kunt u op de onderstaande link klikken.<br>
                Bevestig je account: <a href='https://www.roy-van-der-lee.nl/fleurtop/verify.php?accountverification=$encrypted_txt&key=$key'>Registreer account</a><br><br>
                Met vriendelijk groet,<br>
                Fleurt Op";

    $mail->send();

    echo "<script type='text/javascript'> document.location = 'thankyou.php'; </script>";
    exit();
?>