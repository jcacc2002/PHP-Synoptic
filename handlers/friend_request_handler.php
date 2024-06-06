<?php
session_start();
include('../config.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && isset($_GET['request_id'])) {
    $action = $_GET['action'];
    $request_id = $_GET['request_id'];

    if ($action == 'accept' || $action == 'decline') {
        // Fetch the friend request
        $query = "SELECT * FROM friend_requests WHERE request_id='$request_id'";
        $result = mysqli_query($conn, $query);
        $request = mysqli_fetch_assoc($result);

        if ($request) {
            $sender_id = $request['sender_id'];
            $receiver_id = $request['receiver_id'];

            if ($action == 'accept') {
                // Update request status
                $query = "UPDATE friend_requests SET status='accepted' WHERE request_id='$request_id'";
                mysqli_query($conn, $query);

                // Add to friends table
                $query1 = "INSERT INTO friends (user_id, friend_id) VALUES ('$receiver_id', '$sender_id')";
                $query2 = "INSERT INTO friends (user_id, friend_id) VALUES ('$sender_id', '$receiver_id')";
                mysqli_query($conn, $query1);
                mysqli_query($conn, $query2);
            } else if ($action == 'decline') {
                // Update request status
                $query = "UPDATE friend_requests SET status='declined' WHERE request_id='$request_id'";
                mysqli_query($conn, $query);
            }
        }
    }
}

header("Location: ../friends.php");
exit();
?>