<?php
require_once 'db.php'; // Ensure this file is in the correct location

$message = '';

function is_password_strong($password) {
    return (strlen($password) >= 8 &&
            preg_match('/[A-Z]/', $password) &&
            preg_match('/[a-z]/', $password) &&
            preg_match('/[0-9]/', $password) &&
            preg_match('/[^A-Za-z0-9]/', $password));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        // All validation passed, proceed with database insertion
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);
            
            if ($stmt->execute()) {
                // Successful registration
                $message = "Sign-up successful. Redirecting to sign-in page...";
                header("Refresh: 2; URL=signin.php"); // Redirect after 2 seconds
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Sign Up</h1>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <input type="text" class="form-control" name="first_name" placeholder="First Name" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="last_name" placeholder="Last Name" required>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
                <small class="form-text text-muted">Password must be at least 8 characters long and contain uppercase, lowercase, number, and special character.</small>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <button type="submit" class="btn btn-primary">Sign Up</button>
        </form>
    </div>

    <script>
    document.querySelector('form').addEventListener('submit', function(e) {
        var password = document.querySelector('input[name="password"]').value;
        var confirmPassword = document.querySelector('input[name="confirm_password"]').value;

        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match');
        }
    });
    </script>
</body>
</html>