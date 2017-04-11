<?php
    include 'dbConnection.php';
    $conn = getDBConnection("ShoppingCart", "web_user", "s3cr3t");

function getMoviesSearch() {
    global $conn;
    
    $sql = "SELECT movieTitle, releaseYear, length FROM moviesTable WHERE 1";

    if(empty($_GET['Movie']) && empty($_GET['status']) &&
        empty($_GET['Time'])){
        $sql = "SELECT movieTitle, releaseYear, length FROM moviesTable";
    }
    
    if(isset($_GET['Movie'])){
        $sql .=" AND movieTitle LIKE :Movie";
        $namedParameters[':Movie'] = '%' . $_GET['Movie'] . '%';
        }
    if(isset($_GET['Time'])){
        $Time = $_GET['Time'];
        $sql .=" $Time";
        
    }
        
    
    $stmt = $conn -> prepare ($sql);
    $stmt -> execute($namedParameters);
    $Movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return $Movies;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title> Test </title>
    </head>
    <body>
    <h1> ShoppingCart </h1>
    <hr>
    <form>
        Search:   
        Movie Name: <input type="text" name="Movie"/>    
         <select name= "Time">
          <option value="">Select Movie Length</option>
          <option value="AND length < 100">Less Than 100 mins</option>
          <option value="AND length > 100 AND length < 200">Between 100 and 200 mins</option>
          <option value="AND length > 200">More than 200 mins</option>
        </select> 
        <input type="checkbox" name="name" id="name" />
        <label for="name"> Order by Name </label>
                
        <input type="submit" value="Search" />
        
        </form>
    
    
    
    <?php
    $infos = getMoviesSearch();
    echo "<table cell spacing = '40'>";
    foreach($infos as $info){
      echo "<tr><td>" .  $info['movieTitle'] . "</td><td>" . $info['releaseYear']  . "</td><td>" . $info['length']  . "</td></tr>";
            }
    echo "</table>";
    ?>
    
    
    </body>
</html>