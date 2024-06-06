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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['content']) && !isset($_POST['comment_content'])) {
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $query = "INSERT INTO posts (user_id, content) VALUES ('$user_id', '$content')";
    mysqli_query($conn, $query);
}

// Handle comment form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment_content']) && isset($_POST['post_id'])) {
    $comment_content = mysqli_real_escape_string($conn, $_POST['comment_content']);
    $post_id = $_POST['post_id'];
    $parent_comment_id = isset($_POST['parent_comment_id']) ? $_POST['parent_comment_id'] : 'NULL';

    // Handle parent_comment_id properly
    if ($parent_comment_id === 'NULL') {
        $query = "INSERT INTO comments (post_id, user_id, parent_comment_id, content) VALUES ('$post_id', '$user_id', NULL, '$comment_content')";
    } else {
        $query = "INSERT INTO comments (post_id, user_id, parent_comment_id, content) VALUES ('$post_id', '$user_id', '$parent_comment_id', '$comment_content')";
    }

    mysqli_query($conn, $query);
}

// Fetch posts from the user and their friends
$query = "
    SELECT p.post_id, p.content, p.created_at, u.username, p.user_id 
    FROM posts p 
    JOIN users u ON p.user_id = u.user_id 
    WHERE p.user_id = '$user_id' 
    OR p.user_id IN (SELECT friend_id FROM friends WHERE user_id = '$user_id')
    ORDER BY p.created_at DESC
";
$posts = mysqli_query($conn, $query);

// Fetch comments for a specific post
function fetch_comments($post_id, $conn) {
    $query = "SELECT c.comment_id, c.content, c.created_at, u.username, c.user_id, c.parent_comment_id 
                FROM comments c 
                JOIN users u ON c.user_id = u.user_id 
                WHERE c.post_id = '$post_id' 
                ORDER BY c.created_at ASC";
    return mysqli_query($conn, $query);
}

function display_comments($comments, $post_id, $parent_id = NULL) {
    $html = '';
    foreach ($comments as $comment) {
        if ($comment['parent_comment_id'] == $parent_id) {
            $html .= '<div class="comment" style="margin-left: ' . ($parent_id ? '40px' : '0') . ';">';
            $html .= '<p><strong>' . htmlspecialchars($comment['username']) . ':</strong> ' . htmlspecialchars($comment['content']) . '</p>';
            $html .= '<p><small class="text-muted">' . $comment['created_at'] . '</small></p>';
            $html .= '<form method="POST" action="main_feed.php" style="margin-bottom: 10px;">
                        <input type="hidden" name="post_id" value="' . $post_id . '">
                        <input type="hidden" name="parent_comment_id" value="' . $comment['comment_id'] . '">
                        <div class="form-group">
                            <textarea class="form-control" name="comment_content" placeholder="Reply to this comment..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Reply</button>
                        </form>';
            $html .= display_comments($comments, $post_id, $comment['comment_id']);
            $html .= '</div>';
        }
    }
    return $html;
}

// Function to get the number of likes for a post
function getLikeCount($post_id, $conn) {
    $query = "SELECT COUNT(*) as like_count FROM likes WHERE post_id = '$post_id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['like_count'];
}
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
                <?php if ($post['user_id'] == $user_id): ?>
                    <button class="btn btn-warning" onclick="editPost('<?php echo $post['post_id']; ?>', '<?php echo htmlspecialchars($post['content']); ?>')">Edit</button>
                    <form method="POST" action="handlers/delete_post_handler.php" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this post?');">
                        <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                <?php endif; ?>
                <!-- Like Button -->
                <button class="btn btn-primary btn-sm like-btn" data-post-id="<?php echo $post['post_id']; ?>">Like</button>
                <span id="like-count-<?php echo $post['post_id']; ?>"><?php echo getLikeCount($post['post_id'], $conn); ?></span> Likes
                
                <!-- Comment Form -->
                <form method="POST" action="main_feed.php" style="margin-top: 10px;">
                    <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
                    <div class="form-group">
                        <textarea class="form-control" name="comment_content" placeholder="Write a comment..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Comment</button>
                </form>

                <!-- Display Comments -->
                <?php 
                $comments = fetch_comments($post['post_id'], $conn); 
                echo display_comments(mysqli_fetch_all($comments, MYSQLI_ASSOC), $post['post_id']);
                ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<!-- Edit Post Modal -->
<div class="modal fade" id="editPostModal" tabindex="-1" role="dialog" aria-labelledby="editPostModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="handlers/edit_post_handler.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPostModalLabel">Edit Post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_post_id" name="post_id">
                    <div class="form-group">
                        <label for="edit_content">Content:</label>
                        <textarea class="form-control" id="edit_content" name="content" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script>
function editPost(postId, content) {
    document.getElementById('edit_post_id').value = postId;
    document.getElementById('edit_content').value = content;
    $('#editPostModal').modal('show');
}

// Like button functionality
$(document).on('click', '.like-btn', function() {
    var postId = $(this).data('post-id');
    $.ajax({
        url: 'handlers/like_post.php',
        type: 'POST',
        data: {post_id: postId},
        success: function(response) {
            response = JSON.parse(response);
            if (response.success) {
                $('#like-count-' + postId).text(response.like_count);
            } else {
                console.log(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.log('Error: ' + error);
        }
    });
});
</script>