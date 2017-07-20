<?php

/* Load required lib files. */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$array = $_SESSION['myTweets'];
$filename="myTweets.json";

header('Content-Type: application/json; charset=UTF-8');
header('Content-Disposition: attachment; filename="'.$filename.'";');

echo json_encode($array);

?>