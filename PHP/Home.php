<?php
session_start();
if (!isset($_SESSION['uid'])) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}

echo "Welcome, " . $_SESSION['username'] . "!";
?>

<a href="logout.php">Logout</a>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/Home.css">
    <link rel="stylesheet" href="../CSS/Cards.css">

    <title>Home</title>
</head>
<body>

    <img src="../images/logo.png" alt="Sock-A-Zon Banner" class="banner">

    <nav class="navbar">
        <ul class="links">
            <li><a href="Home.html">Shop</a></li>
            <li><a href="Cart.html">Cart</a></li>
            <li><a href="Profile.html">Profile</a></li>
            <li><a href="Saved.html">Saved</a></li>
            <li><a href="About.html">About</a></li>
        </ul>
    </nav>

    <div style = "padding-top: 200px;">
        <h2> FEATURED</h2>

        <div class="container">
            <div class="card">
                <div class="image">
                    <img src="../images/<?php echo htmlspecialchars($product['product_image']); ?>" alt="Image">
                </div>
                <div class="content">
                    <div class="title"><?php echo htmlspecialchars($product['name']); ?></div>
                    <div class="description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></div>
                    <div class="description"><?php echo nl2br(htmlspecialchars($product['price'])); ?></div>
                    <div class="buttons">
                        <button>Add to cart</button>
                        <button>Add to saved</button>
                    </div>
                </div>
            </div>
    
            <div class="card">
                <div class="image">
                    <img src="../images/TempSocks.png" alt="Image">
                </div>
                <div class="content">
                    <div class="title"></div>
                    <div class="description"></div>
                    <div class="description"></div>
                    <div class="description"></div>
                    <div class="buttons">
                        <button>Add to cart</button>
                        <button>Add to saved</button>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="image">
                    <img src="../images/TempSocks.png" alt="Image">
                </div>
                <div class="content">
                    <div class="title"></div>
                    <div class="description"></div>
                    <div class="description"></div>
                    <div class="description"></div>
                    <div class="buttons">
                        <button>Add to cart</button>
                        <button>Add to saved</button>
                    </div>
                </div>
            </div>
        </div>

        
    </div>


        <div>
        <h2>FOR YOU:</h2>
        </div>

    <div class="container">
        <div class="card">
            <div class="image">
                <img src="../images/TempSocks.png" alt="Image">
            </div>
            <div class="content">
                <div class="title"></div>
                <div class="description"></div>
                <div class="description"></div>
                <div class="description"></div>
                <div class="buttons">
                    <button>Add to cart</button>
                    <button>Add to saved</button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="image">
                <img src="../images/TempSocks.png" alt="Image">
            </div>
            <div class="content">
                <div class="title"></div>
                <div class="description"></div>
                <div class="description"></div>
                <div class="description"></div>
                <div class="buttons">
                    <button>Add to cart</button>
                    <button>Add to saved</button>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="image">
                <img src="../images/TempSocks.png" alt="Image">
            </div>
            <div class="content">
                <div class="title"></div>
                <div class="description"></div>
                <div class="description"></div>
                <div class="description"></div>
                <div class="buttons">
                    <button>Add to cart</button>
                    <button>Add to saved</button>
                </div>
            </div>
        </div>
    </div>


</body>
<?php
    include 'config.php';

    session_start();
    $user_id = $_SESSION['user_id'];
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    try {
    $sql = 'SELECT id, name, description, price, product_image FROM products';

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':pid', $product_id, PDO::PARAM_INT);

    while ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="container">
            <div class="card">
                <div class="image">
                    <img src="../images/<?php echo htmlspecialchars($product['product_image']); ?>" alt="Image">
                </div>
                <div class="content">
                    <div class="title"><?php echo htmlspecialchars($product['title']); ?></div>
                    <div class="description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></div>
                    <div class="buttons">
                        <button>Add to cart</button>
                        <button>Add to saved</button>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
        if (!$product) {
        echo "Product not found.";
        exit;
        }
    } catch (PDOException $e) {
    echo "Error fetching product: " . $e->getMessage();
    exit;
}

?>