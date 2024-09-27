<?php
require_once 'User.php';

$user = new User();
$admin_email = "admin@admin.com";
$admin_password = "Admin123#@!";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $user->signUp("Admin", "User", $admin_email, $admin_password, $admin_password);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Admin</title>
</head>
<body>
    <h1>Create Admin User</h1>
    <form method="POST">
        <button type="submit">Create Admin</button>
    </form>
</body>
</html>
