<?php
require_once 'db.php'; // Make sure this path is correct

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Fetch unique categories (if you decide to add a category column later)
// $categories_sql = "SELECT DISTINCT category FROM products";
// $categories_result = $conn->query($categories_sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Categories</title>
    <!-- Box Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <!-- Categories css -->    
    <link rel="stylesheet" href="../css/categories_page.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>

<section class="category" id="category">

    <!-- Category Side nav -->
    <div class="category_sidenav">
        <div class="sidenav_categories">
            <h4 class="">Categories</h4>
            <div class="category_accessories">
                <ul>
                    <li><a href="#coats_jackets">Dairy Products</a></li>
                    <li><a href="#shoes">Beef</a></li>
                    <li><a href="#belts">Pork</a></li>
                    <li><a href="#wallets">Agricultural</a></li>
                </ul>
                <div class="border_bottom"></div>
            </div>
        </div>
    </div>

    <!-- Category Products -->
    <div class="category_products">

        <!-- Accessories -->
        <div class="heading">
            <h2 id="shoes">Pork</h2>
        </div>

        <div class="accessories">

        <div class="box">
                    <img src="../images/cheese.jpeg" alt="product_image">
                    <h2>Cheese</h2>
                    <span>Ksh 800/=</span>
                    <a href="#"><i class="bx bxs-cart-alt"></i></a>
                </div>

                <div class="box">
                    <img src="../images/cheese.jpeg" alt="product_image">
                    <h2>Cheese</h2>
                    <span>Ksh 800/=</span>
                    <a href="#"><i class="bx bxs-cart-alt"></i></a>
                </div>

                <div class="box">
                    <img src="../images/cheese.jpeg" alt="product_image">
                    <h2>Cheese</h2>
                    <span>Ksh 800/=</span>
                    <a href="#"><i class="bx bxs-cart-alt"></i></a>
                </div>

                <div class="box">
                    <img src="../images/cheese.jpeg" alt="product_image">
                    <h2>Cheese</h2>
                    <span>Ksh 800/=</span>
                    <a href="#"><i class="bx bxs-cart-alt"></i></a>
                </div>


        </div>


        <!-- Beef-->
        <div class="heading">
            <h2 id="belts">Beef</h2>
        </div>

        <div class="accessories">
                <div class="box">
                    <img src="../images/cheese.jpeg" alt="product_image">
                    <h2>Cheese</h2>
                    <span>Ksh 800/=</span>
                    <a href="#"><i class="bx bxs-cart-alt"></i></a>
                </div>

                <div class="box">
                    <img src="../images/cheese.jpeg" alt="product_image">
                    <h2>Cheese</h2>
                    <span>Ksh 800/=</span>
                    <a href="#"><i class="bx bxs-cart-alt"></i></a>
                </div>

                <div class="box">
                    <img src="../images/cheese.jpeg" alt="product_image">
                    <h2>Cheese</h2>
                    <span>Ksh 800/=</span>
                    <a href="#"><i class="bx bxs-cart-alt"></i></a>
                </div>

                <div class="box">
                    <img src="../images/cheese.jpeg" alt="product_image">
                    <h2>Cheese</h2>
                    <span>Ksh 800/=</span>
                    <a href="#"><i class="bx bxs-cart-alt"></i></a>
                </div>
        </div>

        <!-- Dairy Products -->
        <div class="heading">
            <h2 id="coats_jackets">Dairy Products</h2>
        </div>

        <div class="accessories">

                <div class="box">
                    <img src="../images/cheese.jpeg" alt="product_image">
                    <h2>Cheese</h2>
                    <span>Ksh 800/=</span>
                    <a href="#"><i class="bx bxs-cart-alt"></i></a>
                </div>

                <div class="box">
                    <img src="../images/cheese.jpeg" alt="product_image">
                    <h2>Cheese</h2>
                    <span>Ksh 800/=</span>
                    <a href="#"><i class="bx bxs-cart-alt"></i></a>
                </div>

                <div class="box">
                    <img src="../images/cheese.jpeg" alt="product_image">
                    <h2>Cheese</h2>
                    <span>Ksh 800/=</span>
                    <a href="#"><i class="bx bxs-cart-alt"></i></a>
                </div>

                <div class="box">
                    <img src="../images/cheese.jpeg" alt="product_image">
                    <h2>Cheese</h2>
                    <span>Ksh 800/=</span>
                    <a href="#"><i class="bx bxs-cart-alt"></i></a>
                </div>

                <div class="box">
                    <img src="../images/cheese.jpeg" alt="product_image">
                    <h2>Cheese</h2>
                    <span>Ksh 800/=</span>
                    <a href="#"><i class="bx bxs-cart-alt"></i></a>
                </div>
        </div>

    </div>
</section>

  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>