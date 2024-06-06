<?php
session_start();
include('../config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['username'])) {
        die("You need to log in first.");
    }

    $friend_id = $_POST['friend_id'];
    $message = $_POST['message'];
    $username = $_SESSION['username'];

    $query = "SELECT user_id FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    $user_id = $user['user_id'];

    // Insert the message into the database
    $query = "INSERT INTO messages (sender_id, receiver_id, content) VALUES ('$user_id', '$friend_id', '$message')";
    mysqli_query($conn, $query);
}
?>