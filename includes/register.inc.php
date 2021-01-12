<?php
    session_start();
if (isset($_POST['signup-submit'])) {
    require 'dbh.inc.php';

    $username = $_POST['uid'];
    $email = $_POST['mail'];
    $password = $_POST['pwd'];
    $passwordrepeat = $_POST['pwdrepeat'];
    $firstname = $_POST['firstName'];
    $lastname = $_POST['lastName'];
    $straat = $_POST['straatNaam'];
    $huisnummer = $_POST['huisNummer'];
    $toevoeging = $_POST['toevoeging'];
    $postcode = $_POST['postcode'];

    // error berichten in header

    if (empty($username) || empty($email) || empty($password) || empty($passwordrepeat)) {
        header("Location: ../register.php?error=emptyfields&uid=".$username."&mail=".$email);
        exit();
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        header("Location: ../register.php?error=invalidmailuid");
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../register.php?error=invalidmail&uid=".$username);
        exit();
    }
    else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        header("Location: ../register.php?error=invaliduid&mail=".$email);
        exit();
    }
    else if ($password !== $passwordrepeat) {
        header("Location: ../register.php?error=passwordcheck&uid=".$username."&mail=".$email);
        exit();
    }
    else {

        $sql = "SELECT usernameUser FROM User WHERE usernameUser=?";
        $statement = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($statement, $sql)) {
            header("Location: ../register.php?error=sqlerror");
            exit();
        }
        else {
            mysqli_stmt_bind_param($statement, "s", $username);
            mysqli_stmt_execute($statement);
            mysqli_stmt_store_result($statement);
            $resultCheck = mysqli_stmt_num_rows($statement);
            if ($resultCheck > 0) {
                header("Location: ../register.php?error=usertaken&mail=".$email);
                exit();
            }
            else {

                $sql = "SELECT emailUser FROM User WHERE emailUser=?";
                $statement = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($statement, $sql)) {
                    header("Location: ../register.php?error=sqlerror");
                    exit();
                }
                else {
                    mysqli_stmt_bind_param($statement, "s", $email);
                    mysqli_stmt_execute($statement);
                    mysqli_stmt_store_result($statement);
                    $resultCheck = mysqli_stmt_num_rows($statement);
                    if ($resultCheck > 0) {
                        header("Location: ../register.php?error=emailtaken&mail=".$email);
                        exit();
                    }
            else {
                $sql = "INSERT INTO User (usernameUser, emailUser, passUser, firstName, lastName, streetName, houseNumber, houseNumberExtra, postalCode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $statement = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($statement, $sql)) {
                    header("Location: ../register.php?error=sqlerror");
                    exit();
                }
                else {
                    $_SESSION['mail'] = $_POST['mail'];
                    $_SESSION['username'] = $_POST['uid'];
                    $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

                    mysqli_stmt_bind_param($statement, "ssssssiss", $username, $email, $hashedPwd, $firstname, $lastname, $straat, $huisnummer, $toevoeging, $postcode);
                    mysqli_stmt_execute($statement); 
                    header("Location: ../register.php?signup=success");
                    exit();
                }
            }
        }
            }
        }
    }
    mysqli_stmt_close($statement);
    mysqli_close($conn);
}
else {
    header("Location: ../register.php");
    exit();
}