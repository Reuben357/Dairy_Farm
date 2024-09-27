<?php
require_once 'Contact.php';

$contact = new Contact();
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $contact->submitMessage($_POST['name'], $_POST['email'], $_POST['message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us</title>
</head>
<body>
    <h1>Contact Us</h1>
    <form method="POST">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <textarea name="message" placeholder="Your Message" required></textarea>
        <button type="submit">Submit</button>
    </form>
    <?php echo $message; ?>
</body>
</html>
