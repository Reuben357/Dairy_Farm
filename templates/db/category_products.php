<?php
require_once 'db.php';

$conn = getDbConnection();

// Fetch all unique categories
$categories = [];
$result = $conn->query("SELECT DISTINCT category FROM products");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['category'];
    }
}

// If a category is selected, fetch products for that category
$selected_category = isset($_GET['category']) ? $_GET['category'] : null;
$products = [];
if ($selected_category) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE category = ?");
    $stmt->bind_param("s", $selected_category);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Categories</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/category_products.css">
</head>
<body>
    <!-- Sidebar for Categories -->
    <nav class="sidebar">
        <h5 class="text-center">Categories</h5>
        <ul class="nav flex-column">
            <?php foreach ($categories as $category): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($selected_category == $category) ? 'active' : ''; ?>" href="?category=<?php echo urlencode($category); ?>">
                        <?php echo htmlspecialchars(ucfirst($category)); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="content">
        <?php if ($selected_category): ?>
            <h2>Products in <?php echo htmlspecialchars(ucfirst($selected_category)); ?></h2>
            <?php if (!empty($products)): ?>
                <div class="product-grid">
                    <?php foreach ($products as $product): ?>
                        <div class="card product-card">
                            <?php if (!empty($product['image_url'])): ?>
                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>" style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <img src="placeholder.jpg" class="card-img-top" alt="No Image" style="height: 200px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text">$<?php echo htmlspecialchars($product['price']); ?></p>
                                <a href="products_page.php?action=add&id=<?php echo $product['id']; ?>" class="btn btn-primary">Add to Cart</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No products found in this category.</p>
            <?php endif; ?>
        <?php else: ?>
            <h2>Welcome! Please select a category.</h2>
        <?php endif; ?>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->
    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
</body>
</html>
