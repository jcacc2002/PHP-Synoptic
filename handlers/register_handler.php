<?php
session_start();
include('../config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $birthdate = mysqli_real_escape_string($conn, $_POST['birthdate']);

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (username, email, password, first_name, last_name, birthdate) VALUES ('$username', '$email', '$hashed_password', '$first_name', '$last_name', '$birthdate')";
    if (mysqli_query($conn, $query)) {
        $_SESSION['username'] = $username;
        header("Location: ../main_feed.php");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>