<?php
require_once 'db.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product ID from URL
$id = isset($_GET['id']) ? $_GET['id'] : 1;

// Fetch product
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Fetch similar products
$sql = "SELECT * FROM products WHERE id != ? LIMIT 4";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$similar_products = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?></title>
    <link rel="stylesheet" href="../css/products_page.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
</head>
<body>
    <section class="product">
        <div class="product-container">
            <div class="product-image">
                <img id="product-img" src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
                <div class="similar-products">
                    <?php
                    while ($similar = $similar_products->fetch_assoc()) {
                        echo "<img src='" . $similar['image_url'] . "' alt='" . $similar['name'] . "' class='thumbnail'>";
                    }
                    ?>
                </div>
            </div>
            <div class="product-info">
                <h3 class="product_name"><?php echo $product['name']; ?></h3>
                <h5 class="product_price">$<?php echo $product['price']; ?>/=</h5>
                <p class="product_desc"><?php echo $product['description']; ?></p>
                <form action="">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="product_image" value="<?php echo $product['image_url']; ?>">
                    <input type="hidden" name="product_name" value="<?php echo $product['name']; ?>">
                    <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>