<?php
require_once 'db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
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

// Add to cart functionality
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];
    $user_id = $_SESSION['user_id'];
    
    // Fetch product details
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    if ($product) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity']++;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product_id,
                'name' => $product['name'],
                'price' => $product['price'],
                'image_url' => $product['image_url'],
                'quantity' => 1
            ];
        }
        
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Categories</title>
    <style>
        /* Navbar styles */
        .navbar {
            background-color: white;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }


        .navbar ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: inline-flex;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            text-decoration: none;
            color: #a8a62d;;
            font-weight: bold;
            padding: 10px;
            transition: color 0.3s ease;
        }

        nav ul li a:hover {
            color: #a8a62d;
        }

        /* Layout styles */
        .container {
            display: flex;
            margin-top: 20px;
        }

        /* Sidebar styles */
        .sidebar {
            display: flex;
            flex-direction: column;
            width: 250px;
            background-color: white;
            padding: 20px;
            border-right: 1px solid #ddd;
            height: 100vh;
            box-sizing: border-box;
            align-items: center;
            justify-content: flex-start;
        }

        .sidebar h5 {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            margin-bottom: 10px;
        }

        .sidebar .nav-link {
            display: inline-flex;
            padding: 10px;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background-color: #a8a62d;
            color: white;
        }


        /* Main content styles */
        .content {
            flex: 1;
            padding: 20px;
        }

        .content h2 {
            margin-bottom: 20px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .product-card {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            text-align: center;
        }

        .product-card img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }

        .product-card h5 {
            margin-top: 10px;
        }

        .cart-btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #a8a62d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
        }

        .cart-btn:hover {
            background-color: #218838;
        }
        .nav-item{
            display: flex;
            vertical-align: middle;
        }
        nav ul li a {
            display: inline-block;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <ul>
            <li><a href="home_page.php">Home</a></li>
            <li><a href="contactme_page.php">Contact Me</a></li>
            <li><a href="?logout=1" style="color: #dc3545;">Logout</a></li>
        </ul>
    </nav>

    <!-- Sidebar and Main Content -->
    <div class="container">
        <nav class="sidebar">
            <h5 class="text-center">Categories</h5>
            <ul class="nav flex-column">
                <?php foreach ($categories as $category): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($selected_category == $category) ? 'active' : ''; ?>" 
                           href="?category=<?php echo urlencode($category); ?>">
                            <?php echo htmlspecialchars(ucfirst($category)); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <div class="content">
            <?php if ($selected_category): ?>
                <h2>Products in <?php echo htmlspecialchars(ucfirst($selected_category)); ?></h2>
                <?php if (!empty($products)): ?>
                    <div class="product-grid">
                        <?php foreach ($products as $product): ?>
                            <div class="card product-card">
                                <?php if (!empty($product['image_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                         class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                         style="height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <img src="placeholder.jpg" class="card-img-top" alt="No Image" 
                                         style="height: 200px; object-fit: cover;">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                    <p class="card-text">Ksh<?php echo htmlspecialchars($product['price']); ?></p>
                                    <a href="products_page.php?action=add&id=<?php echo $product['id']; ?>" 
                                       class="cart-btn">Add to Cart</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No products found in this category.</p>
                <?php endif; ?>
            <?php else: ?>
                <section class="home" id="home">
                    <div class="home-container container">
                        <div class="home-text">
                            <h1>Dairy Farm</h1>
                            <p>Your one-stop shop for all your animal needs.</p>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
