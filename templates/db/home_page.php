<?php
require_once 'db.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="/templates/css/home_page.css">
    <link rel="stylesheet" href="/templates/css/navbar.css">
</head>
<body>
    <nav class="navbar">
        <h3>Home</h3>
        <ul>
            <li><a href="about.html">About</a></li>
            <li><a href="contact.html">Contact Me</a></li>
            <li><a href="categories.html">Categories</a></li>
            <li><a href="products.html">Products</a></li>
        </ul>
    </nav>
    <div class="product-container">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<a href='products_page.php?id=" . $row["id"] . "' class='product-card'>";
                echo "<img src='" . $row["image_url"] . "' alt='" . $row["name"] . "'>";
                echo "<div class='product-name'>" . $row["name"] . "</div>";
                echo "<div class='product-price'>$" . $row["price"] . "</div>";
                echo "</a>";
            }
        } else {
            echo "0 results";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>