<?php
require_once 'db.php';
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }

    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Set default role as 'user'
    $role = 'user';

    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $message = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match.";
    } elseif ($email === 'admin@admin.com') {
        // Prevent admin from signing up via signup form
        $message = "Admin cannot sign up via this form.";
    } else {
        // If email ends with ".farmer@gmail.com", set the role as 'farmer'
        if (strpos($email, '.farmer@gmail.com') !== false) {
            $role = 'farmer';

            // Insert into the farmers table
            $conn = getDbConnection();
            $stmt = $conn->prepare("INSERT INTO farmers (first_name, last_name, email) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $first_name, $last_name, $email);
            $stmt->execute();
            $stmt->close();
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into users table
        $conn = getDbConnection();
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            // Redirect to signin.php after successful signup
            header("Location: signin.php");
            exit();
        } else {
            $message = "Error creating account. Please try again.";
        }

        $stmt->close();
    }
}

// Generate a new CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../css/signup_page.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
</head>
<body>
<div id="signup-container" class="signup-container">
    <div id="signup-image">
    <img src="../images/chocolate pudding.png" alt="computer icon">
    </div>
    <div id="signup-info" class="signup-info">
        <form action="" method="POST" class="signup-form">
            <?php if (!empty($message)): ?>
                <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <h2>Create Your <span>Account</span></h2>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'];?>">
            <input type="text" placeholder="First Name" class="first-name" name="first_name" required><br>
            <input type="text" placeholder="Last Name" class="last-name" name="last_name" required><br>
            <input type="email" placeholder="Email" class="user-email" name="email" required><br>
            <input type="password" placeholder="Password" class="user-password" name="password" required><br>
            <input type="password" placeholder="Confirm Password" class="confirm-password" name="confirm_password" required><br>
            <input type="submit" value="Sign Up" name="signup_btn" class="signup-btn"><br>
            <p>Already have an account? <a href="signin.php">Sign In</a></p>
        </form>
    </div>
</div>
</body>
</html>
