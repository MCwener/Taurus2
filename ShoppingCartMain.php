<?php
    include 'dbConnection.php';
    $conn = getDBConnection("ShoppingCart", "web_user", "s3cr3t");

function getAllInfo() {
    global $conn;
    $sql = "SELECT movieTitle FROM moviesTable ";
    $statement = $conn->prepare($sql);
    $statement->execute();
    $records = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    return $records;
}




?>











<!DOCTYPE html>
<html>
    <head>
        <title> Test </title>
    </head>
    <body>
    <h1> ShoppingCart </h1>
    <?php
    $infos = getAllInfo();
    echo "<table cell spacing = '40'>";
    foreach ($infos as $info) {
      echo "<tr><td>" .  $info['movieTitle'] . "</td></tr>";
            }
    echo "</table>";
    ?>
    
    
    </body>
</html>