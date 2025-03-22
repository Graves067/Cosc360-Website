<?php
session_start();
if (!isset($_SESSION['uid'])) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}

echo "Welcome, " . $_SESSION['username'] . "!";
?>

<a href="logout.php">Logout</a>