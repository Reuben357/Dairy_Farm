<?php
require_once 'db.php';

// Function to get all customers
function getAllCustomers() {
    $conn = getDbConnection();
    $sql = "SELECT id, first_name, last_name, email FROM users WHERE role = 'user'";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Display list of customers
function displayCustomerList() {
    $customers = getAllCustomers();
    echo "<h2>Customer List</h2>";
    echo "<ul>";
    foreach ($customers as $customer) {
        echo "<li><a href='?customer_id={$customer['id']}'>{$customer['first_name']} {$customer['last_name']} ({$customer['email']})</a></li>";
    }
    echo "</ul>";
}

// Main content
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer List</title>
    <a href="admin_dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        h2 {
            color: #333;
            text-align: center;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            margin: 10px 0;
            font-size: 18px;
        }
        ul li a {
            text-decoration: none;
            color: #007bff;
            padding: 10px;
            display: block;
            background-color: #f9f9f9;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        ul li a:hover {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <?php
    displayCustomerList();
    ?>
</body>
</html>
<?php
$conn = getDbConnection();
$conn->close();
?>
