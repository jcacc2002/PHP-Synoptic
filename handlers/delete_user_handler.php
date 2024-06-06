<?php
session_start();
include('../config.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // Delete related records from other tables
    $queries = [
        "DELETE FROM user_interests WHERE user_id='$user_id'",
        "DELETE FROM comments WHERE user_id='$user_id'",
        "DELETE FROM posts WHERE user_id='$user_id'",
        "DELETE FROM messages WHERE sender_id='$user_id' OR receiver_id='$user_id'",
        "DELETE FROM friend_requests WHERE sender_id='$user_id' OR receiver_id='$user_id'",
        "DELETE FROM friends WHERE user_id='$user_id' OR friend_id='$user_id'",
        "DELETE FROM users WHERE user_id='$user_id'"
    ];

    foreach ($queries as $query) {
        mysqli_query($conn, $query);
    }

    // Destroy the session and redirect to the homepage
    session_destroy();
    header("Location: ../index.php");
    exit();
}
?>