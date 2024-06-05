<?php
include('../config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birthdate = $_POST['birthdate'];

    $query = "INSERT INTO users (email, username, password, first_name, last_name, birthdate) VALUES ('$email', '$username', '$password', '$first_name', '$last_name', '$birthdate')";
    if (mysqli_query($conn, $query)) {
        session_start();
        $_SESSION['username'] = $username; // Store the username in the session
        header("Location: ../interests.php");
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>