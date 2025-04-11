<?php
session_start();
require_once 'config.php'; 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['uid'];
$message = '';

// Handle email update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
    $stmt->bind_param("si", $email, $uid);
    if ($stmt->execute()) {
        $message = "Email updated successfully.";
    } else {
        $message = "Failed to update email.";
    }
    $stmt->close();
}

// Handle profile image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_image'])) {
    if (isset($_FILES['userImages']) && $_FILES['userImages']['error'] === 0) {
        $target_dir = "uploads/";
        $filename = basename($_FILES["userImages"]["name"]);
        $target_file = $target_dir . time() . "_" . $filename;

        if (move_uploaded_file($_FILES["userImages"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("UPDATE users SET userImages = ? WHERE id = ?");
            $stmt->bind_param("si", $target_file, $uid);
            if ($stmt->execute()) {
                $message = "Profile image updated.";
            } else {
                $message = "Failed to update profile image.";
            }
            $stmt->close();
        } else {
            $message = "Image upload failed.";
        }
    }
}

// Handle account deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_account'])) {
    $stmt = $conn->prepare("SELECT userImages FROM users WHERE id = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $stmt->bind_result($profile_image);
    $stmt->fetch();
    $stmt->close();

    if ($profile_image && file_exists($profile_image)) {
        unlink($profile_image);
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $stmt->close();

    session_destroy();
    header("Location: goodbye.php");
    exit();
}

// Get current user info
$stmt = $conn->prepare("SELECT email, userImages FROM users WHERE id = ?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$stmt->bind_result($email, $profile_image);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Settings</title>
</head>
<body>
    <h1>Account Settings</h1>
    <?php if ($message): ?>
        <p><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>

    <h3>Change Email</h3>
    <form method="POST">
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
        <button type="submit" name="update_email">Update Email</button>
    </form>

    <h3>Profile Image</h3>
    <?php if ($profile_image): ?>
        <img src="<?= htmlspecialchars($profile_image) ?>" alt="Profile Image" width="100"><br>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="userImages" required>
        <button type="submit" name="userImages">Upload Image</button>
    </form>

    <h3>Delete Account</h3>
    <form method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.');">
        <button type="submit" name="delete_account" style="color: red;">Delete My Account</button>
    </form>
</body>
</html>