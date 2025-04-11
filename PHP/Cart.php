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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/Cart.css">
    <link rel="stylesheet" href="../CSS/Navbar.css">
    <link rel="stylesheet" href = "../CSS/Cardinfo.css">
    

    <title>Cart</title>
</head>

<body>

    <nav class="navbar">
        <ul class="links">
            <li><a href="Home.html">Shop</a></li>
            <li><a href="Cart.html">Cart</a></li>
            <li><a href="Profile.html">Profile</a></li>
            <li><a href="Saved.html">Saved</a></li>
            <li><a href="About.html">About</a></li>
        </ul>
    </nav>
  
    <div class="cart-container">
       
    </div>
    <div class="checkout-container">
        <input type="number" value="$0.00" readonly>
        <button class="checkout-button">Checkout</button>
    </div>

    <script>

        //incriment ammount button.
        document.querySelectorAll('.cart-item').forEach(item => {
            const decreaseBtn = item.querySelector('.decrease');
            const increaseBtn = item.querySelector('.increase');
            const quantityInput = item.querySelector('.quantity-control input');
            
            decreaseBtn.addEventListener('click', () => {
                let currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                    updateTotal();
                }
            });
            
            increaseBtn.addEventListener('click', () => {
                let currentValue = parseInt(quantityInput.value);
                quantityInput.value = currentValue + 1;
                updateTotal();
            });
            
        });

    const modal = document.getElementById('CardModal');
    const openModal = document.getElementById('checkout-button');
    const closeModal = document.getElementById('closeModal');

    openModal.onClick = function(){
        modal.style.display = "block"
    }

    closeModal.onClick = function(){
        modal.style.display = none;
    }

    document.getElementById('cardForm').onsubmit = function(event){
        alert('Payment Submitted');
        modal.style.display = "none";
        }

    </script>
</body>