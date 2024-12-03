<?php
    function login_supabase($email, $password) {
        // Supabase database configuration
        $SUPABASE_URL = "https://eeifgbieealmhshkdsdr.supabase.co";
        $SUPABASE_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImVlaWZnYmllZWFsbWhzaGtkc2RyIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MzMxODUwODcsImV4cCI6MjA0ODc2MTA4N30.PvRAzvgQ232SD62qTQmix3hP38fGYsLlSKPuQvwV504";
        $url = "$SUPABASE_URL/rest/v1/users?select=*&email=eq.$email";

        $options = [
            'http' => [
                'header' => [
                    "Content-Type: application/json",
                    "Authorization: Bearer $SUPABASE_KEY",
                    "apikey: $SUPABASE_KEY",
                ],
                'method' => 'GET',
            ],
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $users = json_decode($response, true);

        if ($users === false || empty($users)) {
            echo "<script>alert('Email not found')</script>";
            header("refresh:0;url=http://127.0.0.1/startbootstrap-sb-admin-2-gh-pages/login.html");
            exit();
        }

        $user = $users[0];
        $enc_pass = md5($password);

        if ($user['password'] === $enc_pass) {
            header('refresh:0; url=http://127.0.0.1/startbootstrap-sb-admin-2-gh-pages/index.html');
        } else {
            echo "<script>alert('Credentials are incorrect')</script>";
            header("refresh:0;url=http://127.0.0.1/startbootstrap-sb-admin-2-gh-pages/login.html");
            exit();
        }
    }

    require('../api/config/db_connection.php');

    $email = $_POST['email'];
    $pass = $_POST['password'];
    $enc_pass = md5($pass);

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = pg_query($conn, $query);
    $row = pg_fetch_assoc($result);
    if ($row) {
        $query = "SELECT * FROM users WHERE password='$enc_pass'";
        $result = pg_query($conn, $query);
        $row = pg_fetch_assoc($result);
        if ($row) {            
            login_supabase($email, $pass);
            header ('refresh:0; url=http://127.0.0.1/startbootstrap-sb-admin-2-gh-pages/index.html');
        } else {
            echo "<script>alert('credentials are incorrect')</script>";
            header("refresh:0;url=http://127.0.0.1/startbootstrap-sb-admin-2-gh-pages/login.html");
        }
        exit();
    } else {
        echo "<script>alert('Email not found')</script>";
        header("refresh:0;url=http://127.0.0.1/startbootstrap-sb-admin-2-gh-pages/login.html");
        exit();
    }
    pg_close($conn)

?>