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

// Handle friend request acceptance
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accept_friend_id'])) {
    $friend_id = $_POST['accept_friend_id'];
    $query = "INSERT INTO friends (user_id, friend_id) VALUES ('$user_id', '$friend_id'), ('$friend_id', '$user_id')";
    mysqli_query($conn, $query);
    $query = "DELETE FROM friend_requests WHERE (sender_id='$friend_id' AND receiver_id='$user_id') OR (sender_id='$user_id' AND receiver_id='$friend_id')";
    mysqli_query($conn, $query);
}

// Handle friend request rejection
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reject_friend_id'])) {
    $friend_id = $_POST['reject_friend_id'];
    $query = "DELETE FROM friend_requests WHERE (sender_id='$friend_id' AND receiver_id='$user_id') OR (sender_id='$user_id' AND receiver_id='$friend_id')";
    mysqli_query($conn, $query);
}

// Handle adding friend by username
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_friend_username'])) {
    $friend_username = mysqli_real_escape_string($conn, $_POST['add_friend_username']);
    $query = "SELECT user_id FROM users WHERE username='$friend_username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $friend = mysqli_fetch_assoc($result);
        $friend_id = $friend['user_id'];

        $query = "INSERT INTO friend_requests (sender_id, receiver_id) VALUES ('$user_id', '$friend_id')";
        mysqli_query($conn, $query);
    } else {
        echo "<p>User not found.</p>";
    }
}

// Fetch friend requests
$query = "SELECT fr.sender_id, u.username 
            FROM friend_requests fr 
            JOIN users u ON fr.sender_id = u.user_id 
            WHERE fr.receiver_id = '$user_id'";
$friend_requests = mysqli_query($conn, $query);

// Fetch friends and their statuses
$query = "SELECT f.friend_id, u.username, f.status 
            FROM friends f 
            JOIN users u ON f.friend_id = u.user_id 
            WHERE f.user_id = '$user_id'";
$friends = mysqli_query($conn, $query);
?>

<div class="container">
    <h1>Friends</h1>

    <h2>Add Friend by Username</h2>
    <form method="POST" action="friends.php">
        <div class="form-group">
            <label for="add_friend_username">Username:</label>
            <input type="text" class="form-control" id="add_friend_username" name="add_friend_username" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Friend</button>
    </form>

    <h2>Friend Requests</h2>
    <ul>
        <?php while ($request = mysqli_fetch_assoc($friend_requests)): ?>
            <li>
                <?php echo htmlspecialchars($request['username']); ?>
                <form method="POST" action="friends.php" style="display:inline;">
                    <input type="hidden" name="accept_friend_id" value="<?php echo $request['sender_id']; ?>">
                    <button type="submit" class="btn btn-primary btn-sm">Accept</button>
                </form>
                <form method="POST" action="friends.php" style="display:inline;">
                    <input type="hidden" name="reject_friend_id" value="<?php echo $request['sender_id']; ?>">
                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                </form>
            </li>
        <?php endwhile; ?>
    </ul>

    <h2>Your Friends</h2>
    <ul>
        <?php while ($friend = mysqli_fetch_assoc($friends)): ?>
            <li>
                <?php echo htmlspecialchars($friend['username']); ?>
                <form method="POST" action="handlers/update_friend_status.php" style="display:inline;">
                    <input type="hidden" name="friend_id" value="<?php echo $friend['friend_id']; ?>">
                    <select name="status" onchange="this.form.submit()">
                        <option value="normal" <?php if ($friend['status'] == 'normal') echo 'selected'; ?>>Normal</option>
                        <option value="best" <?php if ($friend['status'] == 'best') echo 'selected'; ?>>Best</option>
                    </select>
                </form>
                <form method="POST" action="handlers/remove_friend_handler.php" style="display:inline;">
                    <input type="hidden" name="friend_id" value="<?php echo $friend['friend_id']; ?>">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove this friend?');">Remove</button>
                </form>
            </li>
        <?php endwhile; ?>
    </ul>
</div>

<?php include('includes/footer.php'); ?>