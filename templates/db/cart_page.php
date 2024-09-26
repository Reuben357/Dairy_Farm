<?php
session_start();

// Handle remove product
if (isset($_POST['remove_product'])) {
    $product_id = $_POST['product_id'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
}

// Handle edit quantity
if (isset($_POST['edit_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['product_quantity'];
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] = $new_quantity;
            break;
        }
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
    <link rel="stylesheet" href="/templates/css/cart_page.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
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
            <a href="home_page.php" class="btn btn-primary">Continue Shopping</a>
            <form action="checkout_page.php" method="POST" style="display: inline;">
                <input type="submit" class="btn btn-success" value="Checkout" name="checkout">
            </form>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/cart_page.js"></script>
</body>
</html>