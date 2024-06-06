<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include('config.php');
include('includes/header.php');

if (!isset($_GET['friend_id'])) {
    die("Friend ID not specified.");
}

$friend_id = $_GET['friend_id'];
$username = $_SESSION['username'];
$query = "SELECT user_id FROM users WHERE username='$username'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("User not found.");
}

$user = mysqli_fetch_assoc($result);
$user_id = $user['user_id'];

// Fetch friend's username
$query = "SELECT username FROM users WHERE user_id='$friend_id'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Friend not found.");
}

$friend = mysqli_fetch_assoc($result);
$friend_username = $friend['username'];
?>

<div class="container">
    <h1>Conversation with <?php echo htmlspecialchars($friend_username); ?></h1>
    <div id="chatBox" class="messages" style="height: 300px; overflow-y: scroll; border: 1px solid #ccc; padding: 10px;">
        <!-- Messages will be loaded here by AJAX -->
    </div>
    <form id="messageForm" method="POST">
        <div class="form-group">
            <label for="message">Message:</label>
            <textarea class="form-control" id="message" name="message" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>

<script>
    var friendId = <?php echo $friend_id; ?>;
</script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="scripts/message.js"></script>