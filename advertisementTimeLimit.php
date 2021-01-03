<?php
    require 'includes/dbh.inc.php';
                                                                                                //EXAMPLE: DATE_ADD(2021-1-3 + 2 months) = 2021-1-3
    $sql = "DELETE FROM Advertisement WHERE ((plantCategory = 'stekje' OR plantCategory = 'kiemplant') AND DATE_ADD(date_format(postDate, '%Y-%m-%d'), INTERVAL 2 MONTH) = CURRENT_DATE)
            OR (plantCategory = 'zaad' AND  DATE_ADD(date_format(postDate, '%Y-%m-%d'), INTERVAL 1 YEAR) = CURRENT_TIMESTAMP)";
            
    $conn->query($sql);
?>