<?php
session_start();
require_once 'db.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
$all_products = $result->fetch_all(MYSQLI_ASSOC);

// Get product ID from URL or use the first product
$id = isset($_GET['id']) ? $_GET['id'] : $all_products[0]['id'];

// Fetch selected product
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$selected_product = $result->fetch_assoc();

// Handle add to cart action
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['product_quantity'];
    
    // Fetch product details from database
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    // Create cart item
    $cart_item = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $quantity,
        'image_url' => $product['image_url']
    ];
    
    // Add to cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Check if product already exists in cart
    $product_exists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            $product_exists = true;
            break;
        }
    }
    
    // If product doesn't exist in cart, add it
    if (!$product_exists) {
        $_SESSION['cart'][] = $cart_item;
    }
    
    // Redirect to cart page
    header("Location: cart_page.php");
    exit();
}
    
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage - <?php echo $selected_product['name']; ?></title>
    <link rel="stylesheet" href="../css/products_page.css">
    <title>Homepage - <?php echo $selected_product['name']; ?></title>
    <link rel="stylesheet" href="/templates/css/products_page.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" type="text/css" href="products_page.css">
</head>
<body>
    <section class="product">
        <div class="product-container">
            <div class="product-image">
                <img id="product-img" src="<?php echo $selected_product['image_url']; ?>" alt="<?php echo $selected_product['name']; ?>">
            </div>
            <div class="product-info">
                <h3 class="product_name"><?php echo $selected_product['name']; ?></h3>
                <h5 class="product_price">Ksh <?php echo $selected_product['price']; ?>/=</h5>
                <p class="product_desc"><?php echo $selected_product['description']; ?></p>
                <form method="POST" action="">
                    <input type="hidden" name="product_id" value="<?php echo $selected_product['id']; ?>">
                    <div class="product-quantity">
                        <input type="number" name="product_quantity" value="1" min="1"> Quantity
                    </div>
                    <div class="btn-group">
                        <button class="add-to-cart-btn" type="submit" name="add_to_cart">
                            <i class="fas fa-shopping-cart"></i>
                            add to cart
                        </button>
                        <button class="buy-now-btn">
                            <i class="fas fa-wallet"></i>
                            buy now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="all-products">
        <h2>All Products</h2>
        <div class="product-grid">
            <?php foreach ($all_products as $product): ?>
                <div class="product-item">
                    <a href="?id=<?php echo $product['id']; ?>">
                        <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
                        <h4><?php echo $product['name']; ?></h4>
                        <p>Ksh <?php echo $product['price']; ?>/=</p>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>