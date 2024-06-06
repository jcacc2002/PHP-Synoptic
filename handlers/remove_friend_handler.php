<?php
session_start();
include('../config.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['friend_id'])) {
    $friend_id = $_GET['friend_id'];

    // Get the current user's ID
    $username = $_SESSION['username'];
    $query = "SELECT user_id FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    $user_id = $user['user_id'];

    // Delete the friendship records
    $query1 = "DELETE FROM friends WHERE user_id='$user_id' AND friend_id='$friend_id'";
    $query2 = "DELETE FROM friends WHERE user_id='$friend_id' AND friend_id='$user_id'";
    mysqli_query($conn, $query1);
    mysqli_query($conn, $query2);

    header("Location: ../friends.php");
    exit();
}
?>