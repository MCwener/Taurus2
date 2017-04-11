<?php

function getDBConnection($dbname, $username, $password){

$host = "localhost";
//$dbname = "tech_checkout";
//$username = "web_user";
//$password = "s3cr3t";



try {

//Creating database connection
$dbConn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
// Setting Errorhandling to Exception
$dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

}
catch (PDOException $e) {
    
    echo "There was some problem connecting to the databse!";
    exit();
    
}
return $dbConn;

}

?>