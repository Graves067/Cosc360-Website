<?php
session_start();

// Check if there are items in the cart
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
    exit();
}

include 'config.php'; // Include the database connection

// Get all product IDs from the cart
$productIds = $_SESSION['cart'];

// Create a string of product IDs to query the database
$placeholders = implode(',', array_fill(0, count($productIds), '?'));

// Fetch product details for the products in the cart
$sql = "SELECT pid, name, product_image, price FROM products WHERE pid IN ($placeholders)";
$stmt = $conn->prepare($sql);
$stmt->execute($productIds);

// Display the cart items
echo "<h2>Your Cart</h2>";

while ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $imagePath = "../images/" . htmlspecialchars($product['product_image']);
    ?>
    <div class="cart-item">
        <img src="<?php echo $imagePath; ?>" alt="Image of <?php echo htmlspecialchars($product['name']); ?>" style="width: 100px;">
        <div class="cart-content">
            <div class="cart-title"><?php echo htmlspecialchars($product['name']); ?></div>
            <div class="cart-price"><?php echo nl2br(htmlspecialchars($product['price'])); ?></div>
        </div>
    </div>
    <?php
}
?>

<!-- Optionally, add a checkout button -->
<form action="checkout.php" method="POST">
    <button type="submit">Proceed to Checkout</button>
</form>
