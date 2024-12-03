<?php
    function save_data_supabase($firstname, $lastname, $email, $password){
        //Supabase database configuration
        $SUPABASE_URL = "https://eeifgbieealmhshkdsdr.supabase.co";
        $SUPABASE_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImVlaWZnYmllZWFsbWhzaGtkc2RyIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzMxODUwODcsImV4cCI6MjA0ODc2MTA4N30.PvRAzvgQ232SD62qTQmix3hP38fGYsLlSKPuQvwV504";
        $url = "$SUPABASE_URL/rest/v1/users";
        $data = [
            "firstname" => $firstname,
            "lastname" => $lastname,
            "email" => $email,
            "password" => $password
        ];
        $options = [
            'http' => [
                'header' => [
                    "Content-Type: application/json",
                    "Authorization: Bearer $SUPABASE_KEY",
                    "apikey: $SUPABASE_KEY",
                ],
                'method' => 'POST',
                'content' => json_encode($data),
            ],
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($url, true, $context);
        // $response_data = json_decode($response, true);

        if($response === false) {
            echo "Error: Unable to save data to Supabase";
            exit;
         }
         echo "User has been created."; //. json_encode($response_data);
         
    }

    //DB connection
    require "../api/config/db_connection.php";
    //Get data from register form
    $firstname = $_POST['first_name'];
    $lastname = $_POST['last_name'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $repeat_pass = $_POST['repeat_password'];

    //Validate if passwords match
    if ($pass != $repeat_pass){
        echo "<script>alert('Passwords do not match!')</script>";
        header("refresh:0, url=http://127.0.0.1/startbootstrap-sb-admin-2-gh-pages/register.html");
        exit();
    }

    //Validate if email alredy exists
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = pg_query($conn, $query);
    $row = pg_fetch_assoc($result);
    if ($row){
        echo "<script>alert('Email already exists!')</script>";
        header("refresh:0, url=http://127.0.0.1/startbootstrap-sb-admin-2-gh-pages/register.html");
        exit();
    
    }

    //Encrypt password with md5 hashing algorithm
    $enc_pass = md5($pass);
    //Query to insert data into users table
    $query = "INSERT INTO users (firstname, lastname, email, password) VALUES ('$firstname', '$lastname', '$email', '$enc_pass')";
    //Execute query
    $result = pg_query($conn, $query);

    if ($result) {
        save_data_supabase($firstname, $lastname, $email, $enc_pass);
        echo "<script>alert('Registration successful!')</script>";
        header("refresh:0;url=http://127.0.0.1/startbootstrap-sb-admin-2-gh-pages/login.html");
    } else {
        echo "Registration failed!";
    }
    pg_close($conn);
?>