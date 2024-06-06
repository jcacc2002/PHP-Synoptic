<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include('config.php');
include('includes/header.php');

$username = $_SESSION['username'];
$query = "SELECT user_id FROM users WHERE username='$username'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
$user_id = $user['user_id'];

// Handle friend request form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['friend_username'])) {
    $friend_username = $_POST['friend_username'];
    $query = "SELECT user_id FROM users WHERE username='$friend_username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $friend = mysqli_fetch_assoc($result);
        $friend_id = $friend['user_id'];

        // Insert friend request
        $query = "INSERT INTO friend_requests (sender_id, receiver_id) VALUES ('$user_id', '$friend_id')";
        mysqli_query($conn, $query);
        echo "Friend request sent!";
    } else {
        echo "User not found!";
    }
}

// Fetch friend requests
$query = "SELECT fr.request_id, u.username FROM friend_requests fr JOIN users u ON fr.sender_id = u.user_id WHERE fr.receiver_id='$user_id' AND fr.status='pending'";
$friend_requests = mysqli_query($conn, $query);

// Fetch friends
$query = "SELECT u.username FROM friends f JOIN users u ON f.friend_id = u.user_id WHERE f.user_id='$user_id'";
$friends = mysqli_query($conn, $query);
?>

<div class="container">
    <h1>Friends</h1>

    <!-- Send Friend Request Form -->
    <form method="POST" action="friends.php">
        <div class="form-group">
            <label for="friend_username">Add Friend by Username:</label>
            <input type="text" class="form-control" id="friend_username" name="friend_username" required>
        </div>
        <button type="submit" class="btn btn-primary">Send Friend Request</button>
    </form>

    <h2>Pending Friend Requests</h2>
    <ul>
        <?php while ($request = mysqli_fetch_assoc($friend_requests)): ?>
            <li>
                <?php echo $request['username']; ?>
                <a href="handlers/friend_request_handler.php?action=accept&request_id=<?php echo $request['request_id']; ?>" class="btn btn-success btn-sm">Accept</a>
                <a href="handlers/friend_request_handler.php?action=decline&request_id=<?php echo $request['request_id']; ?>" class="btn btn-danger btn-sm">Decline</a>
            </li>
        <?php endwhile; ?>
    </ul>

    <h2>Friends List</h2>
    <ul>
        <?php while ($friend = mysqli_fetch_assoc($friends)): ?>
            <li><?php echo $friend['username']; ?></li>
        <?php endwhile; ?>
    </ul>
</div>

<?php include('includes/footer.php'); ?>