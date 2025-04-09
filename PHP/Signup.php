<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
            echo "Passwords do not match!";
        } else {
            // Hash the password (for security)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Handle file upload
            $userImage = null; // Default if no file is uploaded
            if (isset($_FILES['userImage']) && $_FILES['userImage']['error'] == 0) {
                // Set upload directory
                $uploadDir = '../uploads/';
                $originalName = basename($_FILES['userImage']['name']);
                $imageExtension = pathinfo($originalName, PATHINFO_EXTENSION);
                $newFileName = uniqid("IMG_", true) . "." . $imageExtension;
                $targetPath = $uploadDir . $newFileName;

                // Check if the file type is allowed
                $allowedTypes = array("jpg", "jpeg", "png", "gif");
                if (!in_array(strtolower($imageExtension), $allowedTypes)) {
                    die("Error: Only JPG, JPEG, PNG, and GIF files are allowed.");
                }

                // Check the file size (limit: 100KB)
                if ($_FILES['userImage']['size'] > 100000) {
                    die("Error: File is too large. Must be under 100KB.");
                }

                // Check and create directory if it does not exist
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // Move uploaded file
                if (move_uploaded_file($_FILES['userImage']['tmp_name'], $targetPath)) {
                    $userImage = $newFileName;
                    echo "User Image: " . htmlspecialchars($userImage);  // Debugging line to check if image was uploaded
                } else {
                    echo "Error uploading image.";
                    exit();
                }
            }

            try {
                // Check if the username already exists
                $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->execute([$username]);

                if ($stmt->rowCount() > 0) {
                    echo "Username already exists!";
                } else {
                    // Insert new user (without image initially)
                    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                    if ($stmt->execute([$username, $hashed_password])) {
                        // Get the UID of the inserted user
                        $uid = $conn->lastInsertId();

                        // Insert user image into userImages table if an image was uploaded
                        if ($userImage) {
                            // Read the image file into a variable
                            $imagedata = file_get_contents($uploadDir . $userImage);
                            
                            // Debugging line: Check if image data is loaded
                            if ($imagedata === false) {
                                die("Error reading image file.");
                            }

                            // Insert the image into userImages table
                            $imageExtension = pathinfo($userImage, PATHINFO_EXTENSION);
                            $stmt = $conn->prepare("INSERT INTO userImages (uid, contentType, image) VALUES (?, ?, ?)");
                            $stmt->bindParam(1, $uid, PDO::PARAM_INT);
                            $stmt->bindParam(2, $imageExtension, PDO::PARAM_STR);
                            $stmt->bindParam(3, $imagedata, PDO::PARAM_LOB);
                            
                            if ($stmt->execute()) {
                                echo "Image inserted into userImages table successfully!";
                            } else {
                                echo "Error inserting image into userImages table.";
                            }
                        }

                        // Set session variables
                        $_SESSION['username'] = $username;
                        $_SESSION['uid'] = $uid;

                        // Redirect to homepage
                        header("Location: Home.php");
                        exit();
                    } else {
                        echo "Error occurred while registering user.";
                    }
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    } else {
        echo "Please fill out all fields.";
    }
}
?>
