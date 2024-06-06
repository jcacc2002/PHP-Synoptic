<?php
session_start();
include('../config.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    $username = $_SESSION['username'];

    // Get the user_id of the logged-in user
    $query = "SELECT user_id FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    $user_id = $user['user_id'];

    // Check if the post belongs to the logged-in user
    $query = "SELECT * FROM posts WHERE post_id='$post_id' AND user_id='$user_id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Delete the post
        $query = "DELETE FROM posts WHERE post_id='$post_id' AND user_id='$user_id'";
        mysqli_query($conn, $query);
    }
}

header("Location: ../main_feed.php");
exit();
?>