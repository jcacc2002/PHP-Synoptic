<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Escape special characters for SQL
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to check if the user exists with the given username and password
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Login successful
        $_SESSION['username'] = $username;
        header("Location: main_feed.php");
    } else {
        // Login failed
        echo "Invalid username or password";
    }
}
?>

<?php include('includes/header.php'); ?>

<div class="container">
    <form method="POST" action="handlers/login_handler.php">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Sign In</button>
        <button type="button" class="btn btn-secondary" onclick="window.location.href='create_account.php'">Create New Account</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>