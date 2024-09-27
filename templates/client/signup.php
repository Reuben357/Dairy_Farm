<?php
require_once 'User.php';

$user = new User();
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $user->signUp($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['password'], $_POST['confirm_password']);
    
    if ($message === "Sign-up successful!") {
        // Redirect to sign-in page after successful sign-up
        header("Location: signin.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="path/to/bootstrap.css">
</head>
<body>
    <h1>Sign Up</h1>
    <form method="POST">
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Sign Up</button>
    </form>
    <p><?php echo $message; ?></p>
</body>
</html>
