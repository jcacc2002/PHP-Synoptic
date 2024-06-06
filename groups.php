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

// Handle group creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['group_name'])) {
    $group_name = mysqli_real_escape_string($conn, $_POST['group_name']);
    $query = "INSERT INTO groups (group_name, created_by) VALUES ('$group_name', '$user_id')";
    mysqli_query($conn, $query);
    $group_id = mysqli_insert_id($conn);

    // Add the creator to the group members
    $query = "INSERT INTO group_members (group_id, user_id) VALUES ('$group_id', '$user_id')";
    mysqli_query($conn, $query);
}

// Fetch groups
$query = "SELECT g.group_id, g.group_name, g.created_at, u.username as creator 
            FROM groups g 
            JOIN users u ON g.created_by = u.user_id 
            ORDER BY g.created_at DESC";
$groups = mysqli_query($conn, $query);
?>

<div class="container">
    <h1>Groups</h1>

    <h2>Create Group</h2>
    <form method="POST" action="groups.php">
        <div class="form-group">
            <label for="group_name">Group Name:</label>
            <input type="text" class="form-control" id="group_name" name="group_name" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Group</button>
    </form>

    <h2>Your Groups</h2>
    <ul>
        <?php while ($group = mysqli_fetch_assoc($groups)): ?>
            <li>
                <?php echo htmlspecialchars($group['group_name']); ?> (created by <?php echo htmlspecialchars($group['creator']); ?>)
                <a href="group_chat.php?group_id=<?php echo $group['group_id']; ?>" class="btn btn-primary btn-sm">Enter Chat</a>
            </li>
        <?php endwhile; ?>
    </ul>
</div>

<?php include('includes/footer.php'); ?>