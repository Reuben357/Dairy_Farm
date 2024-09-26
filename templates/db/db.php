<?php
$servername = "localhost";
$username = "root";
$password = "Trubel_@!";
$dbname = "dairy_products_db";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    // echo "Database created successfully or already exists<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db($dbname);

// Create products table
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    image_url VARCHAR(255)
)";

if ($conn->query($sql) === TRUE) {
    // echo "Table 'products' created successfully or already exists<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Insert sample data if the table is empty
$result = $conn->query("SELECT COUNT(*) as count FROM products");
$row = $result->fetch_assoc();
if ($row['count'] == 0) {
    $sql = "INSERT INTO products (name, price, description, image_url) VALUES
    ('Fresh Milk', 2.50, 'Our fresh milk is sourced from local farms and pasteurized to ensure the highest quality and taste.', '/templates/images/milk.jpeg'),
    ('Cheddar Cheese', 3.00, 'Aged cheddar cheese with a sharp, tangy flavor.', '/templates/images/cheese.jpeg'),
    ('Butter', 2.80, 'Creamy butter made from fresh cream.', '/templates/images/butter.jpeg'),
    ('Greek Yogurt', 1.50, 'Thick and creamy Greek yogurt, perfect for breakfast or as a snack.', '/templates/images/yoghurt.jpeg')";

    if ($conn->multi_query($sql) === TRUE) {
        echo "Sample data inserted successfully<br>";
    } else {
        echo "Error inserting sample data: " . $conn->error . "<br>";
    }
}

$conn->close();
?>