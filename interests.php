<?php
session_start();
include('includes/header.php');

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<div class="container">
    <h1>Select Your Interests</h1>
    <form method="POST" action="handlers/interests_handler.php">
        <div class="form-group">
            <label for="interests">Select your interests:</label>
            <select multiple class="form-control" id="interests" name="interests[]">
                <option>Table-Top Games</option>
                <option>Consoles</option>
                <option>Statues</option>
                <option>Figures</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>