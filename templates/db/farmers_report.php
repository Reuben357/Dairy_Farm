<?php
require_once 'db.php';
session_start();

// Check if the farmer is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'farmer') {
    header("Location: signin.php");
    exit();
}

$farmer_id = $_SESSION['user_id']; // Assuming this is set on login
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $days = intval($_POST['days']);
    $product_category = $_POST['category'];

    $conn = getDbConnection();

    // Prepare the SQL query to calculate the total quantity supplied by the farmer in the specified period
    $stmt = $conn->prepare("
        SELECT SUM(quantity) as total_quantity 
        FROM products 
        WHERE farmer_id = ? 
        AND category = ? 
        AND created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
    ");
    $stmt->bind_param("isi", $farmer_id, $product_category, $days);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $total_quantity = $row['total_quantity'] ?? 0;
        $message = "In the last $days days, you have supplied $total_quantity units of $product_category.";
    } else {
        $message = "No data found for the specified period.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Report</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .report-container {
            width: 100%;
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .report-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
        }
        input[type="number"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        .report-btn {
            background-color: #a8a62d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        .report-btn:hover {
            background-color: #0056b3;
        }
        .alert-info {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
<div id="report-container" class="report-container">
    <h2>Generate Product Report</h2>
    <form action="" method="POST" class="report-form">
        <label for="days">Enter the number of days:</label>
        <input type="number" name="days" id="days" required><br>

        <label for="category">Select product category:</label>
        <select name="category" id="category" required>
            <option value="milk">milk</option>
            <option value="cheese">cheese</option>
            <option value="ice_cream">ice_cream</option>
            <option value="pudding">pudding</option>
            <option value="yoghurt">yoghurt</option>
        </select><br>

        <input type="submit" value="Generate Report" class="report-btn"><br>
        <a href="farmers_dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
    </form>

    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
</div>
</body>
</html>
