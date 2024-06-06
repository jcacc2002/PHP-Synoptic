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

// Handle post form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['content'])) {
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $query = "INSERT INTO posts (user_id, content) VALUES ('$user_id', '$content')";
    mysqli_query($conn, $query);
}

// Fetch posts from the user and their friends
$query = "
    SELECT p.content, p.created_at, u.username 
    FROM posts p 
    JOIN users u ON p.user_id = u.user_id 
    WHERE p.user_id = '$user_id' 
    OR p.user_id IN (SELECT friend_id FROM friends WHERE user_id = '$user_id')
    ORDER BY p.created_at DESC
";
$posts = mysqli_query($conn, $query);
?>

<div class="container">
    <h1>Main Feed</h1>

    <!-- Post Creation Form -->
    <form method="POST" action="main_feed.php">
        <div class="form-group">
            <label for="content">Create a Post:</label>
            <textarea class="form-control" id="content" name="content" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Post</button>
    </form>

    <h2>Your Feed</h2>
    <?php while ($post = mysqli_fetch_assoc($posts)): ?>
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($post['username']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($post['content']); ?></p>
                <p class="card-text"><small class="text-muted"><?php echo $post['created_at']; ?></small></p>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php include('includes/footer.php'); ?>