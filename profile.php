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
?>

<div class="container">
    <h1>Profile</h1>
    <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
    <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
    <p><strong>First Name:</strong> <?php echo $user['first_name']; ?></p>
    <p><strong>Last Name:</strong> <?php echo $user['last_name']; ?></p>
    <p><strong>Birthdate:</strong> <?php echo $user['birthdate']; ?></p>
    <a href="handlers/logout_handler.php" class="btn btn-danger">Log Out</a>
</div>

<?php include('includes/footer.php'); ?>