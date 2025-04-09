<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['uid'])) {
    echo "Welcome, " . $_SESSION['username'] . "!";  
} else {
    if (isset($_SESSION['guest']) && $_SESSION['guest'] == true) {
        echo "Welcome, Guest!";  
    } else {
        header("Location: login.php");
        exit();
    }
}

// Redirect guests to signup page for Cart and Saved page actions
if (isset($_POST['add_to_cart']) || isset($_POST['add_to_saved'])) {
    if (!isset($_SESSION['uid']) && isset($_SESSION['guest']) && $_SESSION['guest'] == true) {
        // Redirect guests to signup page if they try to add to cart or saved
        header("Location: ../Webpages/Signup.html");
        exit();
    }
}

// Handle the add to cart and add to saved functionality
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['pid'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][] = $productId;

    header("Location: Cart.php");
    exit();
}

if (isset($_POST['add_to_saved'])) {
    $productId = $_POST['pid'];

    if (!isset($_SESSION['saved'])) {
        $_SESSION['saved'] = [];
    }

    $_SESSION['saved'][] = $productId;

    header("Location: Saved.php");
    exit();
}

// Cart and Saved page logic
if (isset($_GET['page']) && ($_GET['page'] == 'cart' || $_GET['page'] == 'saved')) {
    if (isset($_SESSION['guest']) && $_SESSION['guest'] == true) {
        header("Location: ../Webpages/Signup.html");
        exit();
    }
}

// Logout functionality
if (isset($_POST['logout'])) {
    session_unset();  // Remove all session variables
    session_destroy();  // Destroy the session
    header("Location: ../Webpages/Start.html");  // Redirect to start.html after logout
    exit();
}
?>

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
            <li><a href="Home.php">Shop</a></li>
            <li><a href="Home.php?page=cart">Cart</a></li> <!-- Cart link with GET parameter -->
            <li><a href="Profile.php">Profile</a></li>
            <li><a href="Home.php?page=saved">Saved</a></li> <!-- Saved link with GET parameter -->
            <li><a href="Search.php">Search</a></li>
        </ul>
    </nav>

    <!-- Logout button if the user is logged in -->
    <?php if (isset($_SESSION['uid'])): ?>
        <form method="POST" action="Home.php">
            <button type="submit" name="logout">Logout</button>
        </form>
    <?php endif; ?>

    <div style="padding-top: 200px;">
        <h2>FEATURED</h2>

        <div class="container">
            <?php
            include 'config.php';  // Include the database connection

            try {
                $sql = 'SELECT pid, name, price, product_image FROM products';
                $stmt = $conn->prepare($sql);  // Use $conn instead of $pdo
                $stmt->execute();  // Execute the query

                while ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $imagePath = "../images/" . htmlspecialchars($product['product_image']);
                    ?>
                    <div class="card">
                        <div class="image">
                            <img src="<?php echo $imagePath; ?>" alt="Image of <?php echo htmlspecialchars($product['name']); ?>">
                        </div>
                        <div class="content">
                            <div class="title"><?php echo htmlspecialchars($product['name']); ?></div>
                            <div class="price"><?php echo nl2br(htmlspecialchars($product['price'])); ?></div>
                            <div class="buttons">
                                <form action="Home.php" method="POST">
                                    <input type="hidden" name="pid" value="<?php echo $product['pid']; ?>">
                                    <button type="submit" name="add_to_cart">Add to Cart</button>
                                </form>
                                <form action="Home.php" method="POST">
                                    <input type="hidden" name="pid" value="<?php echo $product['pid']; ?>">
                                    <button type="submit" name="add_to_saved">Add to Saved</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php
                }

                if ($stmt->rowCount() == 0) {
                    echo "<p>No products available.</p>";
                }

            } catch (PDOException $e) {
                echo "Error fetching products: " . $e->getMessage();
                exit;
            }
            ?>
        </div>
    </div>

</body>
</html>
