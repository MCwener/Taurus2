<?php
    session_start();
    include 'dbConnection.php';
    //$conn = getDBConnection("ShoppingCart", "web_user", "s3cr3t");
    $conn = getDBConnection("team_project", "web_user", "s3cr3t"); // Fernando's Database Connection
    
    
    if(!isset($_SESSION['moviesInCart'])){
        $moviesInCart = array();
        $_SESSION['moviesInCart'] = $moviesInCart;
    }
    
    if(isset($_GET['addToCart']) && isset($_SESSION['moviesInCart'])){
        if(!in_array($_GET['addToCart'],  $_SESSION['moviesInCart'])){
            array_push($_SESSION['moviesInCart'], $_GET['addToCart']);
            
            //echo "The movie " . $_GET['addToCart'] . " was added to the cart";
            
        } //else {
           // echo "The movie " . $_GET['addToCart'] . " is already in the cart";
       // }
    }
    
    if(isset($_GET['ViewCart']) && isset($_SESSION['moviesInCart'])){
        echo "<strong><u>" . "Here is the contents of your cart: " . "</u></strong>";
        
        getCart();
    }

function getMoviesSearch() {
    global $conn;
    
    $sql = "SELECT movieId, movieTitle, releaseYear, length 
            FROM moviesTable join directorTable 
            ON moviesTable.directorId = directorTable.direcId
            WHERE 1";

    if(!isset($_GET['Movie']) && empty($_GET['status']) &&
        empty($_GET['Time'])){
        $sql = "SELECT movieId, movieTitle, releaseYear, length 
                FROM moviesTable join directorTable
                ON moviesTable.directorId = directorTable.direcId";
    }
    
    if(isset($_GET['Movie'])){
        $sql .=" AND movieTitle LIKE :Movie";
        $namedParameters[':Movie'] = '%' . $_GET['Movie'] . '%';
    }
    if(isset($_GET['Decade'])){
        $Decade = $_GET['Decade'];
        $sql.=" $Decade";
        
    }
    
    if(isset($_GET['Time'])){
        $Time = $_GET['Time'];
        $sql .=" $Time";
        
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

function moviesInCart($movieTitle){
    if(in_array($movieTitle, $_SESSION['moviesInCart'])){
        return "disabled ";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Test </title>
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
            
        .jumbotron {
            text-align:center;
            color:white;
            background-image:url("/team_project/img/movie.jpg");
            
        }
        body{
            text-align:center;
            background-image:url("/team_project/img/backgroundColor.jpg");
        }
        input[type =text]{
            border: 2px solid red;
            background-color: #ADD8E6;
            color: black;
        }
        .styled-select select{
        background-color: #ADD8E6;
        color: black;
        }
        table {
          border-collapse: separate;
          border-spacing: 10px 20px;
          background-color:#D5F5E3;
          
        }
        tr, td {
            width: 20px;
            padding-left: 20px;
            padding-right: 20px;
        }
        
        
            
    </style>
    
    <script>
        $(document).ready(function(){
            $("td.title").click(function(e){     //function_td
                //console.log($(this).attr("value")); 
                var movieId = $(this).attr("value");
                $("#Iframe").attr("style", "align: right; padding-left: 10%"); //showing the iframe
                $("#Iframe iframe").attr("src", "movieInfo.php?movieId="+movieId);
            }); 
        });
        
    </script>

    <body>
    <div class="jumbotron">
        <h1> Old Movie House </h1>
    </div>
    <hr>
    
    <div class="alert alert-success" role="alert">
        <?php
         if (isset($_GET['addToCart'])){
           echo "The movie " . $_GET['addToCart'] . " was added to the cart";
         } 
        ?>
    </div>
    
        

    <form>
        <strong>Movie Name:</strong> <input type="text" name="Movie"/>  
        <br/>
        
    <div class ="styled-select">    
        <strong>Movie Length:</strong> 
         <select name= "Time">
          <option value="">Select Movie Length</option>
          <option value="AND length < 100">Less Than 100 mins</option>
          <option value="AND length > 100 AND length < 200">Between 100 and 200 mins</option>
          <option value="AND length > 200">More than 200 mins</option>
         </select> 
        <br/>
        <strong>Movie Decade:</strong>
         <select name= "Decade">
          <option value="">Select Decade</option>
          <option value="AND year(releaseYear) < 1990">1980's</option>
          <option value="AND year(releaseYear) > 1989 AND year(releaseYear) < 2000">1990's</option>
          <option value="AND year(releaseYear) > 1999 AND year(releaseYear) < 2011">2000's</option>
         </select> 
        <br/>
    </div>   
        
        <input type="checkbox" name="Name" id="name" />
        <label for="name"> Order by Name </label>
        <br/>
        <input type="submit" value="Search" />
        
        </form>
    
    
    <div id="parent" style="display: flex">
        <?php
        
        $infos = getMoviesSearch();
        echo "<div width = '50%' style = 'align:left; padding-left: 20%'>";
        echo "<table cell spacing = '40' border = '1'>";
        foreach($infos as $info){
          echo "<tr><td class='title' value = ". $info['movieId']. " >".$info['movieTitle'] ."</td>
                    <td>" . $info['releaseYear'] . "</td>
                    <td>" . $info['length']  . "</td>
                    <td>" . "<a href='ShoppingCartMain.php?addToCart=".$info['movieTitle']."'><button " . moviesInCart($info['movieTitle']) ."type=\"button\" class=\"btn btn-default btn-sm\">
                         <span class=\"glyphicon glyphicon-shopping-cart\" aria-hidden=\"true\"></span> add to cart
                         </button></a>" . "</td></tr>";
                }
        echo "</table>";
        echo "</div>";
        ?>
        <div id= "Iframe" style="display: none">
            <iframe src="movieInfo.php?movieId" width = "400" height = "400"></iframe>    
        </div>
    </div>
    <br/>
    <br/>
    
        
     
    
    
    </body>
    <footer>
        <p><a href='ShoppingCartMain.php?ViewCart=true'><button type = "button" class = "btn btn-default btn-lg"><span aria-hidden = "true"></span>View Cart</a></button>
    </footer>
</html>