<?php
session_start();
include('../config.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['friend_id']) && isset($_POST['status'])) {
    $friend_id = $_POST['friend_id'];
    $status = $_POST['status'];
    $username = $_SESSION['username'];

    // Get the user_id of the logged-in user
    $query = "SELECT user_id FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    $user_id = $user['user_id'];

    // Update the friendship status
    $query = "UPDATE friends SET status = '$status' WHERE user_id = '$user_id' AND friend_id = '$friend_id'";
    mysqli_query($conn, $query);

    $query = "UPDATE friends SET status = '$status' WHERE user_id = '$friend_id' AND friend_id = '$user_id'";
    mysqli_query($conn, $query);
}

header("Location: ../friends.php");
exit();
?>