<?php
session_start();
include('../config.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['friend_id'])) {
    $friend_id = $_POST['friend_id'];
    $username = $_SESSION['username'];

    // Get the user_id of the logged-in user
    $query = "SELECT user_id FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    $user_id = $user['user_id'];

    // Delete all messages between the logged-in user and the specified friend
    $query = "DELETE FROM messages WHERE (sender_id='$user_id' AND receiver_id='$friend_id') OR (sender_id='$friend_id' AND receiver_id='$user_id')";
    mysqli_query($conn, $query);
}

header("Location: ../messages.php");
exit();