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
    <link rel="stylesheet" href="/templates/css/categories.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">Home</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="categories.php">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.html">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.html">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Product Categories</h1>
        
        <!-- If you decide to add categories later, you can use this structure -->
        <!--
        <?php
        // if ($categories_result->num_rows > 0) {
        //     while($category = $categories_result->fetch_assoc()) {
        //         echo "<h2>" . htmlspecialchars($category['category']) . "</h2>";
        //         echo "<div class='row'>";
        //         $result->data_seek(0);
        //         while($product = $result->fetch_assoc()) {
        //             if ($product['category'] == $category['category']) {
        //                 // Product card HTML here
        //             }
        //         }
        //         echo "</div>";
        //     }
        // }
        ?>
        -->

        <!-- For now, we'll display all products without categories -->
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while($product = $result->fetch_assoc()) {
                    echo "<div class='col-md-4 mb-4'>";
                    echo "<div class='card'>";
                    echo "<img src='" . htmlspecialchars($product['image_url']) . "' class='card-img-top' alt='" . htmlspecialchars($product['name']) . "'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>" . htmlspecialchars($product['name']) . "</h5>";
                    echo "<p class='card-text'>$" . number_format($product['price'], 2) . "</p>";
                    echo "<a href='products_page.php?id=" . $product['id'] . "' class='btn btn-primary'>View Details</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>No products found</p>";
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>