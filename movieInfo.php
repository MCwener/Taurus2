<?php
    session_start();
    include 'dbConnection.php';
    //$conn = getDBConnection("ShoppingCart", "web_user", "s3cr3t");
    $conn = getDBConnection("team_project", "web_user", "s3cr3t"); // Fernando's Database Connection

    // get the movie ID through $_GET()
function movieInfo(){
    global $conn;
    if(!empty($_GET['movieId'])){
        $movieID = $_GET['movieId'];
        $sql = "SELECT * 
        FROM `moviesTable` 
        NATURAL JOIN directorTable
        WHERE movieId = $movieID";
        
        $statement = $conn->prepare($sql);
        $statement->execute();
        $records = $statement->fetchAll(PDO::FETCH_ASSOC);
        //return($records);
        //var_dump($records);
        $output = $records[0];
        echo "<font color = 'red'><h2>" . "Movie Title:" . "</h2></font>"; 
        echo "<h4>" . $output['movieTitle'] . "</h4>";
        
        echo "<font color = 'red'><h2>" . "Release Year:" . "</h2></font>";
        echo "<h4>" . $output['releaseYear'] . "</h4>";
        
        echo "<font color = 'red'><h2>" . "Length:" . "</h2></font>";
        echo "<h4>" . $output['length']. " minutes" . "</h4>";
        
        echo "<font color = 'red'><h2>" . "Movie Director:" . "</h2></font>";
        echo "<h4>" . $output['fName'] . " ". $output['lName']  . "</h4>";
        
    
        
        //echo $output['releaseYear'] . $output['length'] . $output['fName'] . $output['lName'];
    }
    else{
        echo "<h2>Nothing was passed</h2>";
    }
}
?>



<!DOCTYPE html>
<html>
    <head>
        <title>Movie Info </title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <!-- File to allow us to use JQuery-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    </head>
    <style>
        h1{
            font-size:30px;
            text-align:center;
        }
        body{
            background:#CACFD2;
        }
    </style>
    <body>
        <div class="jumbotron">
            <h1>Movie Information</h1>
        </div>
        <h2></h2>
        <?php
            movieInfo();
        
        ?>
        
    </body>
</html>