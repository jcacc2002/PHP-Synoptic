<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['username'] = $username;
        header("Location: main_feed.php");
    } else {
        echo "Invalid credentials";
    }
}
?>

<?php include('includes/header.php'); ?>

<div class="container">
    <form method="POST" action="index.php">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Sign In</button>
        <button type="button" class="btn btn-secondary" onclick="window.location.href='create_account_3.php'">Create New Account</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>