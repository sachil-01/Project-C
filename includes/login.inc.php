<?php

if (isset($_POST['login-submit'])) {

    require 'dbh.inc.php';

    $mailid = $_POST['mailId'];
    $password = $_POST['wachtwoord'];

    // Error meldingen in header
    if (empty($mailid) || empty($password)) {
        header("Location: ../loginpagina.php?error=emptyfields");
        exit();
    }
    else {
        $sql = "SELECT * FROM User WHERE usernameUser=? OR emailUser=?;";
        $statement = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($statement, $sql)) {
            header("Location: ../loginpagina.php?error=sqlerror");
            exit();
        }
        else {
            mysqli_stmt_bind_param($statement, "ss", $mailid, $mailid);
            mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);
            if ($row = mysqli_fetch_assoc($result)) {
                $passwordCheck = password_verify($password, $row['passUser']);
                if ($passwordCheck == false) {
                    header("Location: ../loginpagina.php?error=wrongpass");
                    exit();
                }
                else if ($passwordCheck == true) {
                    $verified = $row['verified'];

                    if($verified == 1){
                        // Als je emailadres wilt gebruiken kan je die hier toevoegen
                        session_start();
                        $_SESSION['userId'] = $row['idUser'];
                        $_SESSION['userUid'] = $row['usernameUser'];

                        header("Location: ../index.php?login=succes");
                        exit();
                    }
                    else{
                        header("Location: ../loginpagina.php?error=unverifiedaccount");
                        exit();
                    }
                }
                else {
                    header("Location: ../loginpagina.php?error=wrongpass");
                    exit();
                }
            }
            else {
                header("Location: ../loginpagina.php?error=nouser");
                exit();
            }
        }
    }

}
else {
    header("Location: ../loginpagina.php");
    exit();
}