<?php
require_once 'db.php';
session_start();

$message = '';

function is_password_strong($password) {
    return (strlen($password) >= 8 &&
            preg_match('/[A-Z]/', $password) &&
            preg_match('/[a-z]/', $password) &&
            preg_match('/[0-9]/', $password) &&
            preg_match('/[^A-Za-z0-9]/', $password));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF protection
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }

    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } elseif (!is_password_strong($password)) {
        $message = "Password must be at least 8 characters long and contain uppercase, lowercase, number, and special character.";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        $conn = getDbConnection();
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $message = "Sign-up successful. Redirecting to sign-in page...";
                header("Refresh: 2; URL=signin.php");
                exit();
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Error: " . $conn->error;
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
    <title>Sign Up</title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->

    <!-- Css link -->
    
    <link rel="stylesheet" type="text/css" href="../css/signup_page.css">

</head>
<body>

<div id="signup-container">
    <!--    Left side-->
    <div id="signup-image">
        <img src="../images/strawberry.jpg" alt="computer icon">
    </div>

    <!--    Right side-->
    <div id="signup-info">
        <form action="" method="POST" class="signup-form">
            <h2>Create An <span>Account</span></h2>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'];?>">
            <input type="text" placeholder="First Name" class="user-name" name="first_name"><br>
            <input type="text" placeholder="Last Name" class="user-name" name="last_name"><br>
            <input type="email" placeholder="Email" class="user-email" name="email"><br>
            <input type="password" placeholder="Password" id="password" class="user-password" name="password"><br>
            <input type="password" placeholder="Confirm password" class="confirm-password"
                   name="confirm_password"><br>
            <input type="submit" name="signup_submit" class="signup-btn" value="Sign Up"><br>
            <p>Already have an Account? <a href="signin.php"> Click to Login</a> </p>
        </form>
    </div>
</div>

   
    <script>
    document.getElementById('signup-form').addEventListener('submit', function(e) {
        var password = document.getElementById('password').value;
        var confirmPassword = document.querySelector('input[name="confirm_password"]').value;

        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match');
        }

        // Client-side password strength check
        var strongPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(?=.{8,})/.test(password);
        if (!strongPassword) {
            e.preventDefault();
            alert('Password must be at least 8 characters long and contain uppercase, lowercase, number, and special character.');
        }
    });
    </script>
</body>
</html>