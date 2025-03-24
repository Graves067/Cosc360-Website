<?php
// Start the session to manage the user session
session_start();

// Include your database configuration (this will set up $conn)
include 'config.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure that the required form fields are set
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confpassword'])) {
        // Get form input values
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $confpassword = $_POST['confpassword'];

        // Check if the passwords match
        if ($password !== $confpassword) {
            // Passwords don't match
            echo "Passwords do not match!";
        } else {
            // Hash the password (for security)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            try {
                // Check if the username already exists
                $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->execute([$username]);

                if ($stmt->rowCount() > 0) {
                    // Username already taken
                    echo "Username already exists!";
                } else {
                    // Insert new user into the database
                    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                    if ($stmt->execute([$username, $hashed_password])) {
                        // User successfully registered, log them in
                        $_SESSION['username'] = $username; // Set session variable for the username
                        $_SESSION['uid'] = $conn->lastInsertId(); // Store the user ID in the session

                        // Redirect to home.php after successful signup
                        header("Location: Home.php");
                        exit();
                    } else {
                        // Database error
                        echo "Error occurred while registering user.";
                    }
                }
            } catch (PDOException $e) {
                // Handle any database-related errors
                echo "Error: " . $e->getMessage();
            }
        }
    } else {
        // If required fields are missing
        echo "Please fill out all fields.";
    }
}
?>
