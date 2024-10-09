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

     <!--    Css link-->
     <link rel="stylesheet" href="../css/signin_page.css">

<!-- Box Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/templates/css/signin_page.css">
</head>
<body>

<div id="login-container" class="login-container">
<!--    Left side-->
    <div id="login-image">
        <img src="../images/watermelon.jpg" alt="computer icon">
    </div>

<!--    Right side-->
    <div id="login-info" class="login-info">
        <form action="" method="POST" class="login-form">

        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
            <h2>Welcome <span>Back</span></h2>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'];?>">
            <input type="email" placeholder="Email" class="user-name" name="email"><br>
            <input type="password" placeholder="Password" class="user-password" name="password"><br>
            <input type="submit" value="Login" name="login_btn" class="login-btn"><br>
            <p>Not registered yet? <a href="signup.php"> Create an Account</a> </p>
        </form>
    </div>
</div>
</body>
</html>