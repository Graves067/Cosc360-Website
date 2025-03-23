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
        <img src="../images/<?php echo htmlspecialchars($product['pfp']); ?>" alt="User Profile Picture" class="profile-pic">
        <h2 class="username"><?php echo $name ?></h2>
        <p class="email"><?php echo $email ?></p>
        <button class="settings-btn">Settings</button>
    </div>
</body>
</html>
<?php
include 'config.php';

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    try {
        session_start();
        $user_id = $_SESSION['uid'];

    $sql = 'SELECT uid, username, pfp, email FROM users';
    $stmt = $pdo->prepare($sql);
    $stmt-> bind_param('i', $user_id);
    $stmt->execute();
    $stmt->store_result();


    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':uid', $product_id, PDO::PARAM_INT);


    } catch (PDOException $e) {
    echo ''. $e->getMessage();
    }

?>