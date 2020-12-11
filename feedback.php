<head>
    <title>Feedback form</title>
    <link rel="stylesheet" type="text/css" href="css\style.css">
</head>

<body>
    <div class="feedback">
        <?php
            //Close success popup message
            if(isset($_POST['feedbacksuccess-btn'])){
                $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                $afterFeedbackSuccess = str_replace('?feedback=success', '', $actual_link);
                header('Location: ' . $afterFeedbackSuccess);
                echo "<meta http-equiv='refresh' content='0;url=$afterFeedbackSuccess'>";
                exit();
            }
            if(isset($_GET['feedback'])){
                if($_GET['feedback']=='success'){
                    ?>
                    <div class="blurBackground-success" id="blur-success"></div>

                    <div class="feedback-popup-success" id="feedbackForm-success">
                        <br>
                        <h1>Bedankt voor uw feedback!</h1>
                        <form action="" method="post" class="popup-form">
                            <!-- Close feedback form button -->
                            <br>
                            <button type="submit" class="feedback-submit" name="feedbacksuccess-btn" onclick="feedbackForm('feedbacksuccess')">Sluiten</button>
                            <br><br>
                        </form>
                    </div>
                    <?php
                }
            }
        ?>
        <div class="blurBackground" id="blur"></div>

        <button class="button" onclick="feedbackForm('feedback')">Feedback</button>

        <div class="feedback-popup" id="feedbackForm">
            <h1>Geef uw feedback</h1>
            <form action="PHPMailer/sendmail.php" method="post" class="popup-form">
                <!-- Name -->
                <label for="name">Naam</label><br>
                <input type="text" name="feedbackName" placeholder="Naam..." required><br>
                <!-- Email -->
                <label for="name">Email</label><br>
                <input type="email" name="feedbackEmail" placeholder="E-mailadres..." required><br>
                <!-- Subject -->
                <label for="name">Onderwerp</label><br>
                <input type="text" name="feedbackSubject" placeholder="Onderwerp..." required><br>
                <!-- Message -->
                <label for="name">Bericht</label><br>
                <textarea name="feedbackMessage" placeholder="Type hier uw feedback..." required></textarea><br>
                <!-- Send feedback button -->
                <button type="submit" class="feedback-submit" name="feedback-submit">Stuur feedback</button>
                <!-- Close feedback form button -->
                <button type="submit" class="closefeedback-submit" onclick="feedbackForm('feedback')">Sluiten</button>
            </form>
        </div>
    </div>

    <!-- Open/close form function for feedback button -->
    <script>
        //Checks if feedback form is already open (if true and user clicks on feedback button > feedback form will close)
        var popUp = false;
        function feedbackForm(openForm){
            if(openForm == 'feedback'){
                if(popUp == true){
                    document.getElementById("feedbackForm").style.cssText = "display: none;";
                    document.getElementById("blur").style.cssText = "display: none;";
                    popUp = false;
                    exit;
                }
                document.getElementById("feedbackForm").style.cssText = "display: block;";
                document.getElementById("blur").style.cssText = "display: block;";
                popUp = true;
            } else if(openForm == 'feedbacksuccess'){
                document.getElementById("feedbackForm-success").style.cssText = "display: none;";
                document.getElementById("blur-success").style.cssText = "display: none;";
            }
        }
    </script>
</body>