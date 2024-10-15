<?php
require_once 'db.php';
session_start();

$message = "";

if (isset($_SESSION['user_id'])) {
    header("Location: home_page.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $message = "Both email and password are required.";
    } else {
        if ($email === 'admin@admin.com' && $password === 'Admin123@!') {
            $_SESSION['user_id'] = 'admin';
            $_SESSION['user_role'] = 'admin';
            $_SESSION['last_activity'] = time();
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
                    $_SESSION['last_activity'] = time();

                    // Check if the user is a farmer based on email
                    if (strpos($email, '.farmer@gmail.com') !== false) {
                        // Redirect to the farmers dashboard
                        header("Location: farmers_dashboard.php");
                    } else {
                        // Redirect to the general home page
                        header("Location: signin.php");
                    }
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

$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="../css/signin_page.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
</head>
<body>
<div id="login-container" class="login-container">
    <div id="login-image">
        <img src="../images/bottled milk.png" alt="computer icon">
    </div>
    <div id="login-info" class="login-info">
        <form action="" method="POST" class="login-form">
            <?php if (!empty($message)): ?>
                <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <h2>Welcome <span>Back</span></h2>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'];?>">
            <input type="email" placeholder="Email" class="user-name" name="email" required><br>
            <input type="password" placeholder="Password" class="user-password" name="password" required><br>
            <input type="submit" value="Login" name="login_btn" class="login-btn"><br>
            <p>Not registered yet? <a href="signup.php">Create an Account</a></p>
        </form>
    </div>
</div>
</body>
</html>
