<?php
session_start();
if (isset($_POST['update-submit'])) {
    require 'dbh.inc.php';

    $id = $_SESSION['userId'];
    $username = $_POST['uid'];
    $email = $_POST['mail'];
    $firstname = $_POST['firstName'];
    $lastname = $_POST['lastName'];
    $straat = $_POST['straatNaam'];
    $huisnummer = $_POST['huisNummer'];
    $toevoeging = $_POST['toevoeging'];
    $postcode = $_POST['postcode'];

    $sql = $conn->query("SELECT admin FROM User WHERE idUser = '$id'") or die($conn->error);

    /* fetch associative array */
    while ($row = $sql->fetch_assoc()) {
        if($row["admin"] == 1){
            $biography = "";
        } else if ($row["admin"] == 0){
            $biography = $_POST['userBiography'];
        }
    }

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

    $sql = "SELECT usernameUser FROM User WHERE usernameUser=? AND idUser!=$id";
        $statement = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($statement, $sql)) {
            header("Location: ../profilepage.php?error=sqlerror");
            exit();
        }
        else {
            mysqli_stmt_bind_param($statement, "s", $username);
            mysqli_stmt_execute($statement);
            mysqli_stmt_store_result($statement);
            $resultCheck = mysqli_stmt_num_rows($statement);
            if ($resultCheck > 0) {
                header("Location: ../profilepage.php?error=usertaken&mail=".$email);
                exit();
            }
            else {
                $sql = "SELECT emailUser FROM User WHERE emailUser=? AND idUser!=$id";
                $statement = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($statement, $sql)) {
                    header("Location: ../profilepage.php?error=sqlerror");
                    exit();
                }
                else {
                    mysqli_stmt_bind_param($statement, "s", $email);
                    mysqli_stmt_execute($statement);
                    mysqli_stmt_store_result($statement);
                    $resultCheck = mysqli_stmt_num_rows($statement);
                    if ($resultCheck > 0) {
                        header("Location: ../profilepage.php?error=emailtaken&mail=".$email);
                        exit();
                    }
            else {
                $sql = "UPDATE User SET usernameUser=?, emailUser=?, firstName=?, lastName=?, streetName=?, houseNumber=?, houseNumberExtra=?, postalCode=?, biography=?  WHERE idUser='$id'";
                $statement = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($statement, $sql)) {
                    header("Location: ../profilepage.php?error=sqlerror");
                    exit();
                }                       
                else {
                    mysqli_stmt_bind_param($statement, "sssssisss", $username, $email, $firstname, $lastname, $straat, $huisnummer, $toevoeging, $postcode, $biography);
                    mysqli_stmt_execute($statement); 
                    header("Location: ../profilepage.php?update=success");
                    exit();
                }
            }
    }
}
        }
    mysqli_stmt_close($statement);
    mysqli_close($conn);
}
else {
    header("Location: ../profilepage.php");
    exit();
}