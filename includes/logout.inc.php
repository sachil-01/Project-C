<?php

session_start();
session_unset();
session_destroy();

// hier stuurt de site je terug naar home page
header("Location: ../index.php");