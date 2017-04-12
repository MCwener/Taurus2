<?php
    session_start();
    include 'dbConnection.php';
    $conn = getDBConnection("ShoppingCart", "web_user", "s3cr3t");
    
    if(!isset($_SESSION['moviesInCart'])){
        $moviesInCart = array();
        $_SESSION['moviesInCart'] = $moviesInCart;
    }
    
    if(isset($_GET['addToCart']) && isset($_SESSION['moviesInCart'])){
        if(!in_array($_GET['addToCart'],  $_SESSION['moviesInCart'])){
            array_push($_SESSION['moviesInCart'], $_GET['addToCart']);
            echo "The movie " . $_GET['addToCart'] . " was added to the cart";
        } else {
            echo "The movie " . $_GET['addToCart'] . " is already in the cart";
        }
    }
    
    if(isset($_GET['ViewCart']) && isset($_SESSION['moviesInCart'])){
        echo "Here is the contents of your cart: ";
        getCart();
    }

function getMoviesSearch() {
    global $conn;
    
    $sql = "SELECT movieTitle, releaseYear, length 
            FROM moviesTable join directorTable 
            ON moviesTable.directorId = directorTable.direcId
            WHERE 1";

    if(!isset($_GET['Movie']) && empty($_GET['status']) &&
        empty($_GET['Time']) && empty($_GET['Decade']) &&
            empty($_GET['Name'])){
        $sql = "SELECT movieTitle, releaseYear, length 
                FROM moviesTable join directorTable
                ON moviesTable.directorId = directorTable.direcId";
    }
    
    if(isset($_GET['Movie'])){
        $sql .=" AND movieTitle LIKE :Movie";
        $namedParameters[':Movie'] = '%' . $_GET['Movie'] . '%';
    }
    
    if(isset($_GET['Time'])){
        $Time = $_GET['Time'];
        $sql .=" $Time";
        
    }
    if(isset($_GET['Decade'])){
        $Decade = $_GET['Decade'];
        $sql.=" $Decade";
        
    }
    if(isset($_GET['Name'])){
        $sql.=" ORDER BY movieTitle";   
    }
    
    $stmt = $conn -> prepare ($sql);
    $stmt -> execute($namedParameters);
    $Movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return $Movies;
}

function getCart(){
    foreach($_SESSION['moviesInCart'] as $key=>$value){
        echo "<p>" . $value . "</p>";
    }
}


?>

<!DOCTYPE html>
<html>
    <head>
        <title> Classic Movies </title>
    </head>
    <body>
    <h1> Old Movie House </h1>
    <hr>
    <form>
        Movie Name: <input type="text" name="Movie"/>  
        <br/>
        Movie Length: 
         <select name= "Time">
          <option value="">Select Movie Length</option>
          <option value="AND length < 100">Less Than 100 mins</option>
          <option value="AND length > 100 AND length < 200">Between 100 and 200 mins</option>
          <option value="AND length > 200">More than 200 mins</option>
         </select> 
         
        Movie Decade:
         <select name= "Decade">
          <option value="">Select Decade</option>
          <option value="AND year(releaseYear) < 1990">1980's</option>
          <option value="AND year(releaseYear) > 1989 AND year(releaseYear) < 2000">1990's</option>
          <option value="AND year(releaseYear) > 1999 AND year(releaseYear) < 2011">2000's</option>
         </select> 
        <br/>
        <input type="checkbox" name="Name" id="Name" />
        <label for="name"> Order by Name </label>
        <br/>
        <input type="submit" value="Search" />
        
        </form>
    
    
    
    <?php
    $infos = getMoviesSearch();
    echo "<table cell spacing = '40'>";
    foreach($infos as $info){
      echo "<tr><td>" .  $info['movieTitle'] . "</td>
                <td>" . $info['releaseYear']  . "</td>
                <td>" . $info['length']  . "</td>
                <td>" . "<a href='ShoppingCartMain.php?addToCart=".$info['movieTitle']."'>add to cart</a>" . "</td></tr>";
            }
    echo "</table>";
    ?>
    
    <br/>
    <br/>
    <p><a href='ShoppingCartMain.php?ViewCart=true'> View Cart</a></p>
    
    </body>
</html>