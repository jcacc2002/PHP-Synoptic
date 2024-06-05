<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<?php include('includes/header.php'); ?>

<div class="container">
    <h1>Welcome to the Main Feed, <?php echo $_SESSION['username']; ?>!</h1>
</div>

<?php include('includes/footer.php'); ?>