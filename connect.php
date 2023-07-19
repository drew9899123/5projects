<?php
    $host = 'localhost';
    $user = 'planetco_it65-1';
    $pass = 'it65-1';
    $dbname = 'planetco_it65-1';

    $con = mysqli_connect($host, $user, $pass, $dbname) or die ('Connection Error');
    mysqli_set_charset($con, 'UTF8');
    date_default_timezone_set('Asia/Bangkok');
    session_start();
?>