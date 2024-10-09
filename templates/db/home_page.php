<?php
require_once 'db.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

$stmt = "SELECT * FROM products LIMIT 6";
$shop_products = $conn->query($stmt);

$strawberry = "SELECT * FROM products WHERE name='Strawberry'";
$berry = $conn->query($strawberry);
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

    <!-- TODO: Include the nav_bar -->
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
            <li><a href="#new">New</a></li>
            <li><a href="category_products.php">Categories</a></li>
            <li><a href="contactme_page.php">Contact Me</a></li>

        <!-- Icons -->
        <div class="nav-icons">
            <!-- Create an accounts page -->
            <a href="account_page.php" class="user"><i class="bx bxs-user"></i></a>
            <a href="cart_page.php" class="navbar-cart" id="cartPage"><i class="bx bxs-cart"></i></a>
            <i class="bx bx-menu" id="menu-icon"></i>
        </div>
    </div>
</header>

    <!-- Home -->
     <section class="home" id="home">
        <!-- Home Content -->
        <div class="home-container container">
            <div class="home-text">
                <h1>Dairy Farm</h1>
                <p>Your one-stop shop for all your animal needs. Find a wide variety of quality animal products, from feed and supplies to health care and accessories. Discover the latest information on animal health and well-being. Buy and sell with confidence on our trusted platform.</p>

                <!-- Home Button -->
                 <a href="categories_page.php" class="shop-btn">Shop Now </a>
            </div>

            <!-- Home image -->
             <div class="home-img">
                <img src="../images/butter.png" alt="" class="mySlides">
             </div>
        </div>
     </section>

     <!-- Featured  -->
      <section class="featured" id="featured">
        <!-- Heading -->
         <div class="heading">
            <h2>Beef <span>Products</span></h2>
         </div>

         <!-- Featured Products -->
          <div class="featured-container container">
            <div class="box">
                <img src="../images/butter.jpg" alt="">
                <div class="text">
                    <h2>New Collection <br>Of Beef</h2>
                    <a href="#">View More</a>
                </div>
            </div>

            <div class="box">
                <div class="text">
                    <h2>20% Discount <br>On a KG</h2>
                    <a href="#">View More</a>
                </div>
                <?php 
                if($berry->num_rows > 0) {
                    while($row = $berry->fetch_assoc()){ 
                    echo "<img src='" .$row["image_url"] ."' alt='" . $row["name"] . "'>";
                    }
                }
                ?>
                <!-- <img src="../images/cheese.jpeg" alt=""> -->
            </div>
          </div>
      </section>

      

      <!-- Shop -->
       <section class="shop" id="shop">
        <div class="heading">
            <h2>Shop <span>Now</span></h2>
        </div>

        <!-- Shop Content -->
       <div class="shop-container container">
        <?php while($row= $shop_products->fetch_assoc()){ ?>
            <div class="box">
            <img src="<?php echo $row['image_url']; ?>" alt="product_image">
            <img src="../images/<?php echo $row['image_url']; ?>" alt="product_image">
            <h2> <?php echo $row['name']; ?></h2>
            <span><?php echo $row['price']; ?></span>
            <a href="<?php echo "products_page.php?id=". $row['id']; ?>"><i class="bx bxs-cart-alt"></i></a>
        </div>
        <?php } ?>

       </section>


       <!-- New Arrivals -->
        <section class="new " id="new">
            <div class="heading">
                <h2><span>New</span> Arrivals</h2>
            </div>

            <!-- Shop Content -->
             <div class="shop-container container">
                <div class="box">
                    <img src="../images/watermelon.png" alt="">
                    <h2>Watermelon</h2>
                    <span>Ksh 800/=</span>
                    <a href="#"><i class="bx bxs-cart-alt"></i></a>
                </div>

                <div class="box">
                    <img src="../images/strawberry.png" alt="">
                    <h2>Strawberry </h2>
                    <span>Ksh 800/=</span>
                    <a href="#"><i class="bx bxs-cart-alt"></i></a>
                </div>

                <div class="box">
                    <img src="../images/cheese.jpeg" alt="">
                    <h2>Cheese</h2>
                    <span>Ksh 800/=</span>
                    <a href="#"><i class="bx bxs-cart-alt"></i></a>
                </div>
             </div>
        </section>
     

        <!-- Footer -->
         <!-- TODO: Include foooter -->

         <section class="footer container">
        <div class="footer-box">
    
            <!-- Logo -->
            <a href="" class="logo">
                <img src="/images/leather.png" alt="">
            </a>
            <div class="social">
                <a href=""><i class="bx bxl-facebook"></i></a>
                <a href=""><i class="bx bxl-twitter"></i></a>
                <a href=""><i class="bx bxl-instagram"></i></a>
                <a href=""><i class="bx bxl-youtube"></i></a>
            </div>
        </div>
        <div class="footer-box">
            <h3>Pages</h3>
            <a href="#home">Home</a>
            <a href="#featured">Featured</a>
            <a href="#shop">Shop</a>
            <a href="#new">New</a>
    
        </div>
        <div class="footer-box">
            <h3>Legal</h3>
            <a href="#">Privacy</a>
            <a href="#">Refund Policy</a>
            <a href="#">Terms of Use</a>
            <a href="#">Disclaimer</a>
        </div>
    
        <div class="footer-box">
            <h3>Branches</h3>
            <p>Nakuru</p>
            <p>ELdoret</p>
        </div>
    </section>


    <script src="/templates/js/home_page.js"></script>

         <!-- Glider cdn -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/glider-js/1.7.8/glider.min.js"></script>

   
</body>
</html>