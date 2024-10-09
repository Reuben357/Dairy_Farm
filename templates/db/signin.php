<?php
require_once 'db.php';
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF protection
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $message = "Both email and password are required.";
    } else {
        // Check for admin login
        if ($email === 'admin@admin.com' && $password === 'Admin123@!') {
            $_SESSION['user_id'] = 'admin';
            $_SESSION['user_role'] = 'admin';
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $conn = getDbConnection();
            $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_role'] = $user['role'];
                    $message = "Sign-in successful.";
                    // Redirect to home page
                    header("Location: home_page.php");
                    exit();
                } else {
                    $message = "Invalid email or password.";
                }
            } else {
                $message = "Invalid email or password.";
            }
            $stmt->close();
        }
    }
}

// Generate CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/templates/css/signin_page.css">
</head>
<body>
    <div id="login-container" class="login-container">
    <div id="login-image">
        <img src="/templates/images/milk.jpg" alt="computer icon">
    </div>
    <div id="login-info" class="login-info">
        <h1 class="mb-4">Sign In</h1>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary">Sign In</button>
        </form>
        <p class="login-character">Don't have an account? <a href="signup.php">Sign up here</a></p>
        </div>
    </div>
</body>
</html>