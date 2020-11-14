<?php
    include('header.php')
?>

<head>
    <title>Thank you</title>
    <link rel="stylesheet" type="text/css" href="css\RegisterStyle.css">
</head>
<body>
    <div class="verified">
        <p>
            <?php
                $verifiedPic = "Verified2.jpg";
                $unverifiedPic = "Verified.jpg";
                //checks if the url contains a verification code
                if(isset($_GET['verificationcode'])){
                    //proces verification
                    $accountverification = $_GET['accountverification'];
                    
                    //change 2nd and 3rd parameter to 'root' when working local!
                    $mysqli = mysqli_connect('localhost', 'royvan1q_user_dekas', 'Bossex123!', 'royvan1q_websitedekas');
                
                    $resultSet = $mysqli->query("SELECT verified, usernameUsers FROM users WHERE verified = 0 AND usernameUsers = '$accountverification' LIMIT 1");

                    if($resultSet->num_rows == 1){
                        //validate the email
                        $update = $mysqli->query("UPDATE users SET verified = 1 WHERE usernameUsers = '$accountverification' LIMIT 1");

                        if($update){
                            echo '<div class="oops">
                                    <h1>Gelukt!</h1>
                                  </div><br>';
                            echo "Uw account is geverifieerd. U kunt nu inloggen.";
                            echo '<img src="images/'.$verifiedPic.'" alt=""/>';
                        }else{
                            echo $mysqli->error;
                        }
                    }else{
                        echo '<div class="oops">
                                <img src="images/Logo green2.png" alt="">
                                <img src="images/Logo green2.png" alt="">
                                <h1>PS!</h1>
                              </div><br>';
                        echo "Dit account is ongeldig of al geverifieerd.";
                        echo '<img src="images/'.$unverifiedPic.'" alt=""/>';
                    }
                }
                else
                {
                    die("Oops! Er ging iets fout. (Error: 404)");
                }
            ?>
        </p>
    </div>
</body>

<?php
    include('footer.php')
?>