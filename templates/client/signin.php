<?php
require_once 'User.php';

$user = new User();
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = $user->signIn($_POST['email'], $_POST['password']);
    
    if ($result === true) {
        // Redirect to user homepage or dashboard after successful sign-in
        header("Location: homepage.php"); // Change this to your user-specific page if needed
        exit();
    } else {
        $message = $result; // Display error message if sign-in fails
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign In</title>
    <link rel="stylesheet" href="path/to/bootstrap.css">
</head>
<body>
    <h1>Sign In</h1>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Sign In</button>
    </form>
    <p><?php echo $message; ?></p>
</body>
</html>
