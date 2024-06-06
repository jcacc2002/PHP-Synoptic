<?php
session_start();
include('../config.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!isset($_SESSION['username'])) {
        die("You need to log in first.");
    }

    $friend_id = $_GET['friend_id'];
    $username = $_SESSION['username'];

    $query = "SELECT user_id FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    $user_id = $user['user_id'];

    // Fetch the conversation between the logged-in user and the friend
    $query = "SELECT m.content, u.username AS sender, m.sent_at FROM messages m JOIN users u ON m.sender_id = u.user_id WHERE (m.sender_id='$user_id' AND m.receiver_id='$friend_id') OR (m.sender_id='$friend_id' AND m.receiver_id='$user_id') ORDER BY m.sent_at";
    $messages = mysqli_query($conn, $query);

    while ($message = mysqli_fetch_assoc($messages)) {
        echo "<div><strong>{$message['sender']}:</strong> {$message['content']} <small><i>{$message['sent_at']}</i></small></div>";
    }
}
?>