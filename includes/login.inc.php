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
        $sql = "SELECT * FROM users WHERE usernameUsers=? OR emailUsers=?;";
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
                $passwordCheck = password_verify($password, $row['passUsers']);
                if ($passwordCheck == false) {
                    header("Location: ../loginpagina.php?error=wrongpass");
                    exit();
                }
                else if ($passwordCheck == true) {

                    // Als je emailadres wilt gebruiken kan je die hier toevoegen
                    session_start();
                    $_SESSION['userId'] = $row['idUsers'];
                    $_SESSION['userUid'] = $row['usernameUsers'];

                    header("Location: ../index.php?login=succes");
                    exit();
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