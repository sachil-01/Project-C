<?php
    include('header.php');
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <!-- Somehow I got an error, so I comment the title, just uncomment to show -->
    <!-- <title>Responsive Sidebar Menu</title> -->
    <link rel="stylesheet" href="css/sidebarmenu.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  </head>
  <body>
    <input type="checkbox" id="check">
    <label for="check">
      <i class="fas fa-times" id="btn"></i>
      <i class="fas fa-bars" id="cancel"></i>
    </label>
    <div class="sidebar">
      <header>Menu</header>
      <a href="#" class="active">
      <i class="fas fa-leaf"></i>
        <span>Ruilen?</span>
      </a>
      <a href="#">
        <i class="fas fa-link"></i>
        <span>Advertentie</span>
      </a>
      <a href="#">
        <i class="fas fa-stream"></i>
        <span>Overview</span>
      </a>
      <a href="#">
      <i class="material-icons">chat</i>
        <span>Forum</span>
      </a>
      <a href="faqpagina.php">
        <i class="far fa-question-circle"></i>
        <span>FAQ</span>
      </a>
      <a href="#">
        <i class="fas fa-sliders-h"></i>
        <span>Services</span>
      </a>
      <a href="#">
        <i class="far fa-envelope"></i>
        <span>Contact</span>
      </a>
    </div>
      <h1 style="text-align:center;">Hoe werkt het?</h1>
    </div>
</body>
</html>
