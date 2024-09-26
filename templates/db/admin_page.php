<?php
// config.php
$host = 'localhost';
$dbname = 'inventory_db';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// login.php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email === 'admin@admin.com' && $password === 'Admin123#@!') {
        $_SESSION['user'] = 'admin';
        header('Location: admin_page.php');
        exit;
    } else {
        $error = "Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
</head>
<body>
    <h1>Admin Login</h1>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>

<?php
// dashboard.php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'admin') {
    header('Location: admin_page.php');
    exit;
}

require_once 'config.php';

$categories = ['Dairy Products', 'Beef', 'Agricultural Products'];

$selectedCategory = isset($_GET['category']) ? $_GET['category'] : null;

$sql = "SELECT * FROM products";
if ($selectedCategory) {
    $sql .= " WHERE category = :category";
}

$stmt = $pdo->prepare($sql);
if ($selectedCategory) {
    $stmt->bindParam(':category', $selectedCategory);
}
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
        .product-card {
            border: 1px solid #ccc;
            padding: 10px;
        }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <a href="logout.php">Logout</a>
    <h2>Categories</h2>
    <a href="dashboard.php">All Categories</a>
    <?php foreach ($categories as $category): ?>
        <a href="dashboard.php?category=<?= urlencode($category) ?>"><?= htmlspecialchars($category) ?></a>
    <?php endforeach; ?>

    <h2>Products</h2>
    <a href="add_product.php">Add New Product</a>
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <p>Category: <?= htmlspecialchars($product['category']) ?></p>
                <p>Description: <?= htmlspecialchars($product['description']) ?></p>
                <p>Quantity: <?= htmlspecialchars($product['quantity']) ?></p>
                <a href="edit_product.php?id=<?= $product['id'] ?>">Edit</a>
                <a href="delete_product.php?id=<?= $product['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                <form action="update_quantity.php" method="POST">
                    <input type="hidden" name="id" value="<?= $product['id'] ?>">
                    <input type="number" name="quantity" placeholder="Add quantity">
                    <input type="submit" value="Update">
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>

<?php
// add_product.php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];

    $sql = "INSERT INTO products (name, category, description, quantity) VALUES (:name, :category, :description, :quantity)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':category' => $category,
        ':description' => $description,
        ':quantity' => $quantity
    ]);

    header('Location: dashboard.php');
    exit;
}

$categories = ['Dairy Products', 'Beef', 'Agricultural Products'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>
<body>
    <h1>Add Product</h1>
    <form method="POST">
        <input type="text" name="name" placeholder="Product Name" required><br>
        <select name="category" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category ?>"><?= $category ?></option>
            <?php endforeach; ?>
        </select><br>
        <textarea name="description" placeholder="Description" required></textarea><br>
        <input type="number" name="quantity" placeholder="Quantity" required><br>
        <input type="submit" value="Add Product">
    </form>
</body>
</html>

<?php
// edit_product.php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once 'config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];

    $sql = "UPDATE products SET name = :name, category = :category, description = :description, quantity = :quantity WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':category' => $category,
        ':description' => $description,
        ':quantity' => $quantity,
        ':id' => $id
    ]);

    header('Location: dashboard.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute([':id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

$categories = ['Dairy Products', 'Beef', 'Agricultural Products'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
</head>
<body>
    <h1>Edit Product</h1>
    <form method="POST">
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required><br>
        <select name="category" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category ?>" <?= $product['category'] == $category ? 'selected' : '' ?>><?= $category ?></option>
            <?php endforeach; ?>
        </select><br>
        <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea><br>
        <input type="number" name="quantity" value="<?= htmlspecialchars($product['quantity']) ?>" required><br>
        <input type="submit" value="Update Product">
    </form>
</body>
</html>

<?php
// delete_product.php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once 'config.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

header('Location: dashboard.php');
exit;

// update_quantity.php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $quantity = $_POST['quantity'];

    $stmt = $pdo->prepare("UPDATE products SET quantity = quantity + :quantity WHERE id = :id");
    $stmt->execute([
        ':quantity' => $quantity,
        ':id' => $id
    ]);
}

header('Location: dashboard.php');
exit;

// logout.php
session_start();
session_destroy();
header('Location: login.php');
exit;