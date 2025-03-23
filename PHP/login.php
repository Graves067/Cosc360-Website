
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
    <form action = "Home.html" method="POST">
<label for="username">Username:</label>
<input type="text" id="username" name="username" required>
<br><br>
<label for="password">Password:</label>
<input type="text" id="password" name="password" required>
<br><br>

<button type="submit">Login</button>

    </form>

    <?php
    include 'config.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        try{

            $pdo = new PDO($conn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = 'SELECT uid, password FROM users where username = ?';

            $usernameQ = $pdo -> quote($username);
            $passwordQ = $pdo -> quote($password);

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if($user){

                if(password_verify($password, $user['password'])){
                    $_SESSION['uid'] = $uid;
                    $_SESSION['username'] = $username;
                    header('Location:Home.php');
                    exit();
                }else{
                    echo "Invalid password";
                }
            }else{
                echo "No User Found";
            }
            
        } catch (mysqli_sql_exception $e) {
            echo ''. $e->getMessage() .'';
        }

    }

    ?>
</div>
</body>
</html>