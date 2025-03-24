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

// Check if there are saved items
if (!isset($_SESSION['saved']) || empty($_SESSION['saved'])) {
    echo "No items saved.";
    exit();
}

include 'config.php'; 

// Get all product IDs from the saved list
$productIds = $_SESSION['saved'];
$placeholders = implode(',', array_fill(0, count($productIds), '?'));

// Fetch product details for the saved items
$sql = "SELECT pid, name, product_image, price FROM products WHERE pid IN ($placeholders)";
$stmt = $conn->prepare($sql);
$stmt->execute($productIds);

echo "<h2>Your Saved Items</h2>";

while ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $imagePath = "../images/" . htmlspecialchars($product['product_image']);
    ?>
    <div class="saved-item">
        <img src="<?php echo $imagePath; ?>" alt="Image of <?php echo htmlspecialchars($product['name']); ?>" style="width: 100px;">
        <div class="saved-content">
            <div class="saved-title"><?php echo htmlspecialchars($product['name']); ?></div>
            <div class="saved-price"><?php echo nl2br(htmlspecialchars($product['price'])); ?></div>
        </div>
    </div>
    <?php
}
?>

<!-- Optionally, add a checkout button -->
<form action="checkout.php" method="POST">
    <button type="submit">Proceed to Checkout</button>
</form>
