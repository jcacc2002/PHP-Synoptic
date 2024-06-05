<?php
session_start();
include('../config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['username'])) {
        die("You need to log in first.");
    }

    $interests = $_POST['interests'];
    $username = $_SESSION['username'];

    // Fetch the user ID
    $query = "SELECT user_id FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $user = mysqli_fetch_assoc($result);
        $user_id = $user['user_id'];

        // Insert interests into user_interests table
        foreach ($interests as $interest) {
            $query = "INSERT INTO user_interests (user_id, interest) VALUES ('$user_id', '$interest')";
            mysqli_query($conn, $query);
        }
        header("Location: ../main_feed.php");
    } else {
        echo "Error fetching user ID: " . mysqli_error($conn);
    }
}
?>