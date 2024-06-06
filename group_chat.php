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

if (!isset($_GET['group_id'])) {
    header("Location: groups.php");
    exit();
}

$group_id = $_GET['group_id'];

// Fetch group details
$query = "SELECT * FROM groups WHERE group_id='$group_id'";
$result = mysqli_query($conn, $query);
$group = mysqli_fetch_assoc($result);

// Check if the current user is the creator of the group
$is_creator = $group['created_by'] == $user_id;

// Handle message submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['content'])) {
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $query = "INSERT INTO group_messages (group_id, user_id, content) VALUES ('$group_id', '$user_id', '$content')";
    mysqli_query($conn, $query);
}

// Handle group name update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_group_name'])) {
    $new_group_name = mysqli_real_escape_string($conn, $_POST['new_group_name']);
    $query = "UPDATE groups SET group_name='$new_group_name' WHERE group_id='$group_id'";
    mysqli_query($conn, $query);
    $group['group_name'] = $new_group_name;
}

// Handle group deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_group'])) {
    $query = "DELETE FROM groups WHERE group_id='$group_id'";
    mysqli_query($conn, $query);
    header("Location: groups.php");
    exit();
}

// Fetch group messages
$query = "SELECT gm.message_id, gm.content, gm.sent_at, u.username 
          FROM group_messages gm 
          JOIN users u ON gm.user_id = u.user_id 
          WHERE gm.group_id = '$group_id' 
          ORDER BY gm.sent_at ASC";
$messages = mysqli_query($conn, $query);
?>

<div class="container">
    <h1>Group Chat: <?php echo htmlspecialchars($group['group_name']); ?></h1>

    <?php if ($is_creator): ?>
        <!-- Edit Group Name Form -->
        <h2>Edit Group Name</h2>
        <form method="POST" action="group_chat.php?group_id=<?php echo $group_id; ?>">
            <div class="form-group">
                <input type="text" class="form-control" name="new_group_name" value="<?php echo htmlspecialchars($group['group_name']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Name</button>
        </form>

        <!-- Delete Group Form -->
        <h2>Delete Group</h2>
        <form method="POST" action="group_chat.php?group_id=<?php echo $group_id; ?>" onsubmit="return confirm('Are you sure you want to delete this group?');">
            <input type="hidden" name="delete_group" value="1">
            <button type="submit" class="btn btn-danger">Delete Group</button>
        </form>
    <?php endif; ?>

    <h2>Messages</h2>
    <ul>
        <?php while ($message = mysqli_fetch_assoc($messages)): ?>
            <li>
                <strong><?php echo htmlspecialchars($message['username']); ?>:</strong> <?php echo htmlspecialchars($message['content']); ?>
                <small class="text-muted"><?php echo $message['sent_at']; ?></small>
            </li>
        <?php endwhile; ?>
    </ul>

    <h2>Send a Message</h2>
    <form method="POST" action="group_chat.php?group_id=<?php echo $group_id; ?>">
        <div class="form-group">
            <textarea class="form-control" name="content" placeholder="Write a message..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>