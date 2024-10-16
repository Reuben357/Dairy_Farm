<?php
require_once 'db.php';
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

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: signin.php");
    exit();
}

// Query for all products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Query for 3 products
$stmt = "SELECT * FROM products LIMIT 3";
$shop_products = $conn->query($stmt);

// Query for a single product
$stmt = "SELECT * FROM products WHERE name='Discount Ice Cream' LIMIT 1";
$discount = $conn->query($stmt);

$stmt = "SELECT * FROM products WHERE category='milk' LIMIT 1";
$dairy = $conn->query($stmt);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../css/home_page.css">

    <!-- Box Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <!-- Glider cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/glider-js/1.7.8/glider.min.css">

</head>

<body>
    <!-- Navbar -->
    <header>
        <div class="nav container">
            <!-- Logo -->
            <a href="#" class="logo">
                <img src="" alt="">
            </a>

            <!-- Nav Links -->
            <ul class="navbar">
                <li><a href="home_page.php" class="active">Home</a></li>
                <li><a href="#featured">Featured</a></li>
                <li><a href="category_products.php">Categories</a></li>
                <li><a href="contactme_page.php">Contact Me</a></li>
                <li><a href="?logout=1">Logout</a></li>
            </ul>

            <!-- Icons -->
            <div class="nav-icons">
               <!-- Cart -->
                <a href="cart_page.php" class="navbar-cart" id="cartPage"><i class="bx bxs-cart"></i></a>
                <i class="bx bx-menu" id="menu-icon"></i>
            </div>
        </div>
    </header>

    <!-- Home Section -->
    <section class="home" id="home">
        <div class="home-container container">
            <div class="home-text">
                <h1>Dairy Farm Management System</h1>
                <p>Your one-stop shop for all your animal needs. Find a wide variety of quality animal products, from feed and supplies to health care and accessories.</p>

                <!-- Home Button -->
                <a href="category_products.php" class="shop-btn">Shop Now</a>
            </div>

            <!-- Home Image -->
            <div class="home-img">
                <img src="../images/butter.png" alt="" class="mySlides">
            </div>
        </div>
    </section>

    <!-- Featured Section -->
    <section class="featured" id="featured">
        <div class="heading">
            <h2>New <span>Arrivals</span></h2>
        </div>

        <div class="featured-container container">

             <?php while($row= $dairy->fetch_assoc()){ ?>
                <div class="box">
                <img src="<?php echo $row['image_url']; ?>" alt="product_image">  
                <div class="text">
                    <h2>New Collection <br>Of Milk</h2>
                    <a href="<?php echo "products_page.php?id=". $row['id']; ?>">View More</a>
                </div>
                </div>
            <?php } ?>


            <?php while($row= $discount->fetch_assoc()){ ?>
                <div class="box">
                <div class="text">
                    <h2>20% Discount <br>On Icecream</h2>
                    <a href="<?php echo "products_page.php?id=". $row['id']; ?>">View More</a>
                </div>
                    <img src="<?php echo $row['image_url']; ?>" alt="product_image">  
                </div>
            <?php } ?>

        </div>
    </section>

    <!-- Shop Section -->
    <section class="shop" id="shop">
        <div class="heading">
            <h2>Shop <span>Now</span></h2>
        </div>

        <div class="shop-container container">
            <?php while($row= $shop_products->fetch_assoc()){ ?>
                <div class="box">
                    <img src="<?php echo $row['image_url']; ?>" alt="product_image">
                    <h2> <?php echo $row['name']; ?></h2>
                    <span><?php echo $row['price']; ?></span>
                    <a href="<?php echo "products_page.php?id=". $row['id']; ?>"><i class="bx bxs-cart-alt"></i></a>
                </div>
            <?php } ?>
        </div>
    </section>

    <!-- Footer -->
    <section class="footer container">
        <!-- Footer content remains the same -->
    </section>
</body>
</html>