<?php
session_start();
include('../config.php');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];

    // Check if the user has already liked the post
    $query = "SELECT * FROM likes WHERE post_id = '$post_id' AND user_id = '$user_id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {
        // Add a new like
        $query = "INSERT INTO likes (post_id, user_id) VALUES ('$post_id', '$user_id')";
        if (!mysqli_query($conn, $query)) {
            echo json_encode(['success' => false, 'message' => 'Error adding like: ' . mysqli_error($conn)]);
            exit();
        }
    } else {
        // Remove the like
        $query = "DELETE FROM likes WHERE post_id = '$post_id' AND user_id = '$user_id'";
        if (!mysqli_query($conn, $query)) {
            echo json_encode(['success' => false, 'message' => 'Error removing like: ' . mysqli_error($conn)]);
            exit();
        }
    }

    // Get the updated like count
    $like_count = getLikeCount($post_id, $conn);

    echo json_encode(['success' => true, 'like_count' => $like_count]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

function getLikeCount($post_id, $conn) {
    $query = "SELECT COUNT(*) as like_count FROM likes WHERE post_id = '$post_id'";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo json_encode(['success' => false, 'message' => 'Error fetching like count: ' . mysqli_error($conn)]);
        exit();
    }
    $row = mysqli_fetch_assoc($result);
    return $row['like_count'];
}
?>