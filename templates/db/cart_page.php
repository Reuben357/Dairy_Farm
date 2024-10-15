<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// Check session timeout (30 minutes)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header("Location: signin.php");
    exit();
}
$_SESSION['last_activity'] = time();

// Handle remove product
if (isset($_POST['remove_product'])) {
    $product_id = $_POST['product_id'];
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Handle edit quantity
if (isset($_POST['edit_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['product_quantity'];
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Calculate total
$total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="../css/cart_page.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
    <style>
     /* Variables */
:root{
    --main-color: #a8a62d;
    --main-light-color: #c0b15c;
    --container-color: #f8f7fc;
    --text-color: #1a1d22;
    --bg-color: #fff;
}
.nav{
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 0;
}
.logo img{
    width: 70px;
}
.navbar{
    display: flex;
    column-gap: 2rem;

}

.navbar a{
    position: relative;
    color: var(--text-color);
    font-size: 1rem;
    font-weight: 500;
    transition: all 0.2s linear;
    text-decoration: none;

}
.navbar a:hover,
.navbar .active {
    color: var(--main-color);
}




    </style>

<body>


<header>
        <div class="nav container">
            <!-- Logo -->
            <a href="#" class="logo">
                <img src="" alt="">
            </a>

            <!-- Nav Links -->
            <ul class="navbar">
                <li><a href="home_page.php" class="active">Home</a></li>
                <li><a href="category_products.php">Categories</a></li>
                <li><a href="contactme_page.php">Contact Me</a></li>
            </ul>

            <!-- Icons -->
            <div class="nav-icons">
                <!-- Account and Cart -->
                <a href="cart_page.php" class="navbar-cart" id="cartPage"><i class="bx bxs-cart"></i></a>
                <i class="bx bx-menu" id="menu-icon"></i>
            </div>
        </div>
    </header>

    <section class="cart container">
        <div class="container">
            <h2>Your Cart</h2>
            <hr>
        </div>
 
        <table class="cart-table">
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>

            <?php
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    ?>
                    <tr>
                        <td>
                            <div class="cart-info">
                                <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>">
                                <div>
                                    <p><?php echo $item['name']; ?></p>
                                    <small>Ksh <?php echo $item['price']; ?>/=</small>
                                    <br>
                                    <form method="POST" action="">
                                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                        <input type="submit" name="remove_product" class="remove-btn" value="remove">
                                    </form>
                                </div>
                            </div>
                        </td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <input type="number" name="product_quantity" value="<?php echo $item['quantity']; ?>" min="1">
                                <input type="submit" class="edit-btn" value="Edit" name="edit_quantity">
                            </form>
                        </td>
                        <td>
                            <span>Ksh</span>
                            <span class="cart-product-price"><?php echo $item['price'] * $item['quantity']; ?>/=</span>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='3'>Your cart is empty</td></tr>";
            }
            ?>
        </table>

        <div class="cart-total">
            <table>
                <tr>
                    <td>Total</td>
                    <td>Ksh <?php echo $total; ?>/= </td>
                </tr>
            </table>
        </div>

        <div class="action-buttons">
            <a href="home_page.php" class="shopping-btn" style="text-decoration: none;">Continue Shopping</a>
            <form action="checkout_page.php" method="POST" style="display: inline;">
                <input type="submit" class="checkout-btn" value="Checkout" name="checkout">
            </form>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/cart_page.js"></script>
</body>
</html>