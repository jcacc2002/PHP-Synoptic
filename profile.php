<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include('config.php');
include('includes/header.php');

$username = $_SESSION['username'];
$query = "SELECT * FROM users WHERE username='$username'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Handle profile update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $birthdate = mysqli_real_escape_string($conn, $_POST['birthdate']);

    $query = "UPDATE users SET email='$email', username='$username', first_name='$first_name', last_name='$last_name', birthdate='$birthdate' WHERE user_id='{$user['user_id']}'";
    mysqli_query($conn, $query);

    $_SESSION['username'] = $username; // Update session username
    header("Location: profile.php");
    exit();
}
?>

<div class="container">
    <h1>Profile</h1>
    <form method="POST" action="profile.php">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="form-group">
            <label for="first_name">First Name:</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="birthdate">Birthdate:</label>
            <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($user['birthdate']); ?>" required>
        </div>
        <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
    </form>
    
    <!-- Delete Profile Button -->
    <form method="POST" action="handlers/delete_user_handler.php" onsubmit="return confirm('Are you sure you want to delete your profile? This action cannot be undone.');">
        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
        <button type="submit" name="delete_profile" class="btn btn-danger mt-3">Delete Profile</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>