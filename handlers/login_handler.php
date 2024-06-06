<?php
session_start();
include('../config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Escape special characters for SQL
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to check if the user exists with the given username and password
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Login successful
        $_SESSION['username'] = $username;
        header("Location: ../main_feed.php");
    } else {
        // Login failed
        echo "Invalid username or password";
    }
}
?>