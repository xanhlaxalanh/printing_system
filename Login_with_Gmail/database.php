<?php 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "SSPS";

    $conn = mysqli_connect($servername, $username, $password, $database);
    if(!$conn) {
        echo ("Can't connect");
    }
?>