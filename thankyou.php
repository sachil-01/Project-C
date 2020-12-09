<?php
    session_start();
    include('header.php');
    if (isset($_POST['resend-mail'])) {
        include('PHPMailer/sendmail.php');
    }
?>

<head>
    <title>Thank you</title>
    <link rel="stylesheet" type="text/css" href="css\RegisterStyle.css">
</head>
<body>
    <div class="thankyou">
        <h1>Registratie gelukt</h1>
        <p>Bedankt voor het registreren. We hebben een verificatie-e-mail gestuurd naar het opgegeven adres.<br/>
           Indien u deze e-mail niet ontvangen hebt, kijk dan eerst bij uw ongewenste e-mails of spam.</p>
        <form method="post">
            <button type="submit" name="resend-mail">Stuur verificatie opnieuw</button>
        </form>
        <img src="images/ThankYouPic.jpg" alt="">
    </div>
</body>

<?php
    include('footer.php');
    include('feedback.php');
?>