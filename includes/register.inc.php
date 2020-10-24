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

        $sql = "SELECT usernameUsers FROM users WHERE usernameUsers=?";
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
                $sql = "INSERT INTO users (usernameUsers, emailUsers, passUsers, firstName, lastName) VALUES (?, ?, ?, ?, ?)";
                $statement = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($statement, $sql)) {
                    header("Location: ../register.php?error=sqlerror");
                    exit();
                }
                else {
                    $verificationcode = md5(uniqid(rand(), true)); //You would have to hash about 18,000,000,000,000,000,000 items before you had a 50% likelyhood of getting two of the same hash. That's one hash every millisecond for 584 million years.                                                                                                 //str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 10)), 0, 10 -> shuffles the 10 random chosen chars
                    $_SESSION['code'] = $verificationcode;
                    $_SESSION['mail'] = $_POST['mail'];
                    $_SESSION['username'] = $_POST['uid'];
                    $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

                    mysqli_stmt_bind_param($statement, "sssss", $username, $email, $hashedPwd, $firstname, $lastname);
                    mysqli_stmt_execute($statement); 
                    header("Location: ../register.php?signup=success");
                    exit();
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