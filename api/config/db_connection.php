<?php
    $servername = "localhost";
    $port = "5432";
    $username = "postgres";
    $password = "1234";
    $dbname = "entrega_tercer_corte";

    $data = "
        host=$servername
        port=$port 
        dbname=$dbname 
        user=$username
        password=$password
        ";

    $conn = pg_connect($data);

    if (!$conn) {
        die("Connection failed: " . pg_last_error());
    } else {
        // echo "Connected successfully";
    }
?>