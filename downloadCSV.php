<?php

/* Load required lib files. */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$array = $_SESSION['myTweets'];
$filename="myTweets.csv";
$delimiter=";";

header('Content-Type: application/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="'.$filename.'";');

$f = fopen('php://output', 'w');

    foreach ($array as $line) {
        $line=json_decode(json_encode($line),true);
        fputcsv($f, $line, $delimiter);
    }

?>