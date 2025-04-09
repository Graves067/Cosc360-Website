<?php
include 'config.php'; // Include your database connection file

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    // Redirect to the login page if the user is not logged in
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['uid']; // Get user ID from session

try {
    // Prepare the SQL query to fetch user details (username and password)
    $sql = 'SELECT username, password FROM users WHERE uid = :uid';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT); // Bind user ID
    $stmt->execute();

    // Fetch the user data
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $username = $user['username'];
        $password = $user['password'];
    } else {
        echo "User not found!";
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Profile.css">
    <title>Profile</title>
</head>
<body>
    <header>
        <nav class="bar">
            <ul class="links">
                <li><a href="Home.html">Shop</a></li>
                <li><a href="Cart.html">Cart</a></li>
                <li><a href="Profile.html">Profile</a></li>
                <li><a href="Saved.html">Saved</a></li>
                <li><a href="About.html">About</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="profile-container">
        <h2 class="username">Username: <?php echo htmlspecialchars($username); ?></h2>
        
        <!-- Form to change username -->
        <form action="ChangeU.php" method="POST">
            <input type="text" name="new_username" placeholder="New Username" required>
            <button type="submit">Change Username</button>
        </form>

        <h2>Password: <?php echo str_repeat('*', strlen($password)); ?></h2>
        
        <!-- Form to change password -->
        <form action="ChangeP.php" method="POST">
            <input type="password" name="new_password" placeholder="New Password" required>
            <button type="submit">Change Password</button>
        </form>
    </div>
</body>
</html>
