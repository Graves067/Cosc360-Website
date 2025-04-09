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

// Initialize searchQuery to empty string if not set
$searchQuery = isset($_POST['searchQuery']) ? $_POST['searchQuery'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/Home.css">
    <link rel="stylesheet" href="../CSS/Cards.css">
    <title>Search Products</title>
</head>
<body>

    <img src="../images/logo.png" alt="Sock-A-Zon Banner" class="banner">

    <nav class="navbar">
        <ul class="links">
            <li><a href="Home.php">Shop</a></li>
            <li><a href="Home.php?page=cart">Cart</a></li> <!-- Cart link with GET parameter -->
            <li><a href="Profile.php">Profile</a></li>
            <li><a href="Home.php?page=saved">Saved</a> </li>
            <li><a href="Search.php">Search</a></li>
        </ul>
    </nav>

    <!-- Logout button if the user is logged in -->
    <?php if (isset($_SESSION['uid'])): ?>
        <form method="POST" action="Home.php">
            <button type="submit" name="logout">Logout</button>
        </form>
    <?php endif; ?>

    <div class="search-container">
        <form method="POST" action="Search.php">
            <input type="text" name="searchQuery" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search for a product...">
            <button type="submit">Search</button>
        </form>
    </div>

    <div style="padding-top: 50px;">
        <h2>Search Results for "<?php echo htmlspecialchars($searchQuery); ?>"</h2>

        <div class="container">
            <?php
            include 'config.php';  // Include the database connection

            if ($searchQuery != '') {
                try {
                    $sql = "SELECT pid, name, price, product_image FROM products WHERE name LIKE ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute(["%$searchQuery%"]);

                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if ($results) {
                        foreach ($results as $product) {
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
                    } else {
                        echo "<p>No products found matching your search.</p>";
                    }

                } catch (PDOException $e) {
                    echo "Error fetching products: " . $e->getMessage();
                }
            } else {
                echo "<p>Please enter a search query.</p>";
            }
            ?>
        </div>
    </div>

</body>
</html>
