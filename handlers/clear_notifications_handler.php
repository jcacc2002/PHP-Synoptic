<?php
session_start();
include('../config.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

$username = $_SESSION['username'];
$query = "SELECT user_id FROM users WHERE username='$username'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
$user_id = $user['user_id'];

// Mark all messages as read
$query = "UPDATE messages SET `read`=TRUE WHERE receiver_id='$user_id' AND `read`=FALSE";
mysqli_query($conn, $query);

// Clear friend requests (optional: you might want to keep them for further processing)
$query = "UPDATE friend_requests SET status='cleared' WHERE receiver_id='$user_id' AND status='pending'";
mysqli_query($conn, $query);

header("Location: ../notifications.php");
exit();
?>