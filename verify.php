<?php
    include('header.php');
    require 'includes/dbh.inc.php';
?>

<head>
    <title>Thank you</title>
    <link rel="stylesheet" type="text/css" href="css\RegisterStyle.css">
</head>
<body>
    <div class="verified">
        <p>
            <?php
                // Include decryption function
                include('encrypt_decrypt.php');
                
                $verifiedPic = "Verified2.jpg";
                $unverifiedPic = "Verified.jpg";
                //checks if the url contains a verification code
                if(isset($_GET['accountverification'])){
                    //proces verification
                    $accountverification = $_GET['accountverification'];
                    $key = $_GET['key'];

                    // Decrypt username with length of username as key
                    $decrypted_txt = encrypt_decrypt('decrypt', $accountverification, $key);

                    $sql = "SELECT verified, usernameUser FROM User WHERE verified = 0 AND usernameUser = '$decrypted_txt' LIMIT 1";
                    $resultSet = mysqli_query($conn, $sql);

                    if($resultSet->num_rows == 1){
                        //validate the email
                        $sql = "UPDATE User SET verified = 1 WHERE usernameUser = '$decrypted_txt'";
                        $update = mysqli_query($conn, $sql);

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
    include('footer.php');
    include('feedback.php');
?>
