<?php
session_start();
if (isset($_POST['update-submit'])) {
    

    require 'dbh.inc.php';

    $id = $_SESSION['userId'];
    $username = $_POST['uid'];
    $email = $_POST['mail'];
    $firstname = $_POST['firstName'];
    $lastname = $_POST['lastName'];

    // error berichten in header

    if (empty($username) || empty($email) || empty($firstname) || empty($lastname)) {
        header("Location: ../profilepage.php?error=emptyfields&uid=".$username."&mail=".$email);
        exit();
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        header("Location: ../profilepage.php?error=invalidmailuid");
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../profilepage.php?error=invalidmail&uid=".$username);
        exit();
    }
    else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        header("Location: ../profilepage.php?error=invaliduid&mail=".$email);
        exit();
    }
            else {
                $sql = "UPDATE users SET usernameUsers=?, emailUsers=?, firstName=?, lastName=? WHERE idUsers='$id'";
                $statement = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($statement, $sql)) {
                    header("Location: ../profilepage.php?error=sqlerror");
                    exit();
                }
                else {

                    mysqli_stmt_bind_param($statement, "ssss", $username, $email, $firstname, $lastname);
                    mysqli_stmt_execute($statement); 
                    header("Location: ../profilepage.php?update=success");
                    exit();
                }
            }
    mysqli_stmt_close($statement);
    mysqli_close($conn);
}
else {
    header("Location: ../profilepage.php");
    exit();
}