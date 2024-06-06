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

// Fetch new messages
$query = "SELECT m.sender_id AS friend_id, m.message_id, m.content, u.username AS sender_username 
            FROM messages m 
            JOIN users u ON m.sender_id = u.user_id 
            WHERE m.receiver_id='$user_id' AND m.read=FALSE";
$new_messages = mysqli_query($conn, $query);

// Fetch new friend requests
$query = "SELECT fr.request_id, u.user_id AS sender_id, u.username AS sender_username 
            FROM friend_requests fr 
            JOIN users u ON fr.sender_id = u.user_id 
            WHERE fr.receiver_id='$user_id' AND fr.status='pending'";
$new_requests = mysqli_query($conn, $query);
?>

<div class="container">
    <h1>Notifications</h1>
    
    <h2>New Messages</h2>
    <ul>
        <?php while ($message = mysqli_fetch_assoc($new_messages)): ?>
            <li>
                <?php echo htmlspecialchars($message['sender_username']); ?>: <?php echo htmlspecialchars($message['content']); ?>
                <a href="message.php?friend_id=<?php echo $message['friend_id']; ?>" class="btn btn-primary btn-sm">View</a>
            </li>
        <?php endwhile; ?>
    </ul>

    <h2>New Friend Requests</h2>
    <ul>
        <?php while ($request = mysqli_fetch_assoc($new_requests)): ?>
            <li>
                <?php echo htmlspecialchars($request['sender_username']); ?>
                <a href="handlers/friend_request_handler.php?action=accept&request_id=<?php echo $request['request_id']; ?>" class="btn btn-success btn-sm">Accept</a>
                <a href="handlers/friend_request_handler.php?action=decline&request_id=<?php echo $request['request_id']; ?>" class="btn btn-danger btn-sm">Decline</a>
            </li>
        <?php endwhile; ?>
    </ul>

    <form method="POST" action="handlers/clear_notifications_handler.php">
        <button type="submit" class="btn btn-warning">Clear Notifications</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>