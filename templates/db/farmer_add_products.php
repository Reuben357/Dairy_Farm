<?php
session_start();

// Check if user is logged in and is a farmer
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'farmer') {
    header("Location: signin.php");
    exit();
}

require_once 'db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $description = trim($_POST['description']);
    $category = $_POST['category'];
    $quantity = intval($_POST['quantity']);
    $farmer_id = $_SESSION['user_id']; // Get the farmer's ID from the session

    // Handle file upload
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = 'uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $filename = uniqid() . '_' . $_FILES['image']['name'];
        $target_file = $upload_dir . $filename;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            // Allow certain file formats
            if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif" ) {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image_url = $target_file;
                } else {
                    $message = "Sorry, there was an error uploading your file.";
                }
            } else {
                $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }
        } else {
            $message = "File is not an image.";
        }
    }

    if (empty($message)) {
        $conn = getDbConnection();
        
        // Start transaction
        $conn->begin_transaction();

        try {
            // First, ensure the farmer exists in the farmers table
            $stmt = $conn->prepare("INSERT IGNORE INTO farmers (id, first_name, last_name, email) SELECT id, first_name, last_name, email FROM users WHERE id = ?");
            $stmt->bind_param("i", $farmer_id);
            $stmt->execute();
            $stmt->close();

            // Now insert the product
            $stmt = $conn->prepare("INSERT INTO products (name, price, description, image_url, category, quantity, farmer_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sdsssii", $name, $price, $description, $image_url, $category, $quantity, $farmer_id);

            if ($stmt->execute()) {
                // Commit transaction
                $conn->commit();
                $message = "Product added successfully.";
            } else {
                throw new Exception("Error executing product insert: " . $stmt->error);
            }

            $stmt->close();
        } catch (Exception $e) {
            // An error occurred, rollback the transaction
            $conn->rollback();
            $message = "Error: " . $e->getMessage();
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Add New Product</h1>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" class="form-control-file" id="image" name="image">
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <select class="form-control" id="category" name="category" required>
                    <option value="milk">milk</option>
                    <option value="cheese">cheese</option>
                    <option value="yoghurt">yoghurt</option>
                    <option value="ice_cream">ice_cream</option>
                    <option value="pudding">pudding</option>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
            <a href="farmers_dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>