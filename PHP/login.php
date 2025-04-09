<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/Home.css">
    <title>Login</title>
</head>
<body>
    <header>    
        <img src="../images/loginSocks.png" alt="Sock-A-Zon Banner" class="bannerLogin">
    </header>
    <div class="log">
    <form action="login.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br><br>

        <button type="submit">Login</button>
    </form>

    <?php
    session_start();  // Start the session
    include 'config.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            $dsn = 'mysql:host=localhost;dbname=sockazon';
            $pdo = new PDO($dsn, 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Query to find user based on the username
            $sql = 'SELECT uid, password FROM users WHERE username = :username';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    // Set session variables
                    $_SESSION['uid'] = $user['uid'];  // Set user ID session
                    $_SESSION['username'] = $username; // Set username session
                    $_SESSION['guest'] = false;
                    // Redirect to Home.php after successful login
                    header('Location: Home.php');
                    exit();
                } else {
                    echo "Invalid password";
                }
            } else {
                echo "No User Found";
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    ?>
    </div>
</body>
</html>
