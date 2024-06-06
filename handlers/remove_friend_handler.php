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

    // Remove the friendship
    $query = "DELETE FROM friends WHERE user_id = '$user_id' AND friend_id = '$friend_id'";
    mysqli_query($conn, $query);

    $query = "DELETE FROM friends WHERE user_id = '$friend_id' AND friend_id = '$user_id'";
    mysqli_query($conn, $query);

    header("Location: ../friends.php");
    exit();
}
?>