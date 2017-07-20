<?php

/* Load required lib files. */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$array = $_SESSION['myTweets'];
$filename="myTweets.xml";
$xml=false;

header('Content-Type: text/xml; charset=UTF-8');
header('Content-Disposition: attachment; filename="'.$filename.'";');

function array2xml($array, $xml = false){

 if($xml === false){
        $xml = new SimpleXMLElement('<user/>');
    }

    foreach($array as $key => $value){
        if(is_object($value))
        {
            $tmp=json_decode(json_encode($value));
            array2xml($tmp, $xml->addChild($key));
        }
        else
        {
        if(is_array($value)){
            array2xml($value, $xml->addChild($key));
        } else {
            $xml->addChild($key, $value);
        }
       }
    }
return $xml->asXML();
}

$xml = array2xml($array, false);

echo $xml;
?>