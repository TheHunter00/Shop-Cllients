<?php
if (isset($_GET["id"])){
    $id=$_GET["id"];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "myshop";
// Create a new connection to the MySQL database
$connection = new mysqli($servername, $username, $password, $database);


$sql="DELETE FROM clients WHERE id=$id";
$connection->query($sql);

}

header("location: /projects/myshop/index.php");
exit;
?>