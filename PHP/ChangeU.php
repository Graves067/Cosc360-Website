<?php
include 'config.php'; // Include your database connection file

session_start();

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['uid']; // Get user ID from session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = trim($_POST['new_username']);

    // Validate input (ensure it's not empty and doesn't contain special characters)
    if (empty($new_username)) {
        echo "Username cannot be empty!";
        exit();
    }

    try {
        // Prepare the SQL query to update the username
        $sql = 'UPDATE users SET username = :username WHERE uid = :uid';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $new_username, PDO::PARAM_STR);
        $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
        
        // Execute the update
        $stmt->execute();

        // Redirect back to profile page with a success message
        header('Location: Profile.php?status=success');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
}
?>
