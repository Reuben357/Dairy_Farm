<?php
require_once 'db.php'; // Ensure db.php is included to connect to the database

// Direct SQL inserts for each product
$sql = "INSERT INTO products (name, price, description, image_url, category, quantity)
        VALUES 
        ('Butter', 2.99, 'Creamy and smooth butter, perfect for baking and cooking.', '/templates/images/butter.jpg', 'dairy', 100),
        ('Cheese', 5.49, 'Rich and flavorful cheese, great for sandwiches and cooking.', '/templates/images/cheese.jpg', 'dairy', 50),
        ('Milk', 1.50, 'Fresh whole milk, full of nutrients.', '/templates/images/milk.jpg', 'dairy', 200),
        ('Yoghurt', 3.99, 'Delicious and healthy yoghurt, available in various flavors.', '/templates/images/yoghurt.jpg', 'dairy', 75)";

// Execute the SQL query
if ($conn->query($sql) === TRUE) {
    echo "Products inserted successfully!";
} else {
    echo "Error inserting products: " . $conn->error;
}

// Close the connection
$conn->close();
?>
