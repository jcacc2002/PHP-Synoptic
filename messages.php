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

// Fetch all unique conversation partners
$query = "
    SELECT DISTINCT
        CASE
            WHEN sender_id = '$user_id' THEN receiver_id
            ELSE sender_id
        END AS friend_id,
        u.username AS friend_username
    FROM messages
    JOIN users u ON u.user_id = CASE WHEN sender_id = '$user_id' THEN receiver_id ELSE sender_id END
    WHERE sender_id = '$user_id' OR receiver_id = '$user_id'
";
$conversations = mysqli_query($conn, $query);
?>

<div class="container">
    <h1>Your Conversations</h1>
    <ul>
        <?php while ($conversation = mysqli_fetch_assoc($conversations)): ?>
            <li>
                <a href="message.php?friend_id=<?php echo $conversation['friend_id']; ?>">
                    <?php echo $conversation['friend_username']; ?>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
</div>

<?php include('includes/footer.php'); ?>