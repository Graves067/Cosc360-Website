<?php
session_start(); // Start the session

// Clear all session variables
unset($_SESSION['username']);
unset($_SESSION['uid']);
unset($_SESSION['guest']);  // Ensure no previous guest session is active

// Set the guest session
$_SESSION['guest'] = true;

// Redirect to Home.php
header("Location: Home.php");
exit();
?>
