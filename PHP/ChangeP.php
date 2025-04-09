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
    $new_password = trim($_POST['new_password']);

    // Validate input (ensure it's not empty)
    if (empty($new_password)) {
        echo "Password cannot be empty!";
        exit();
    }

    // Hash the password before storing it
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    try {
        // Prepare the SQL query to update the password
        $sql = 'UPDATE users SET password = :password WHERE uid = :uid';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
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
