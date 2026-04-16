<?php
    $databaseHost = '10.10.10.20';
    $username = 'phpuser';
    $password = 'IQ3wh--SHp!!gJ6Y';
    $databaseName = 'test';

    $mysqli = mysqli_connect($databaseHost, $username, $password, $databaseName);

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
?>