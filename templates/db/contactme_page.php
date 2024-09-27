<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dairy_products_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$nameErr = $emailErr = $messageErr = "";
$name = $email = $message = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Name
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $nameErr = "Only letters and white space allowed";
        }
    }

    // Validate Email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // Validate Message
    if (empty($_POST["message"])) {
        $messageErr = "Message is required";
    } else {
        $message = test_input($_POST["message"]);
    }

    // If no errors, process the form
    if (empty($nameErr) && empty($emailErr) && empty($messageErr)) {
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        
        $stmt = $conn->prepare("INSERT INTO contact_messages (user_id, name, email, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $name, $email, $message);
        
        if ($stmt->execute()) {
            $successMessage = "Thank you for your message, $name! We'll get back to you soon.";
            $name = $email = $message = ""; // Clear the form
        } else {
            $successMessage = "Oops! Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Me</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../css/contactme_page.css">
    <style>
        .error {color: #FF0000;}
        .success-message {color: #4CAF50; margin-bottom: 20px;}
    </style>
</head>
<body>
    <div class="container">
        <h1>Get in Touch</h1>
        
        <div class="contact-info">
            <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <p>email@example.com</p>
            </div>
            <div class="contact-item">
                <i class="fas fa-phone"></i>
                <p>+254 712 345 678</p>
            </div>
            <div class="contact-item">
                <i class="fas fa-map-marker-alt"></i>
                <p>Kenya, KE</p>
            </div>
        </div>
        
        <?php
        if (!empty($successMessage)) {
            echo "<div class='success-message'>$successMessage</div>";
        }
        ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
                <span class="error"><?php echo $nameErr; ?></span>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                <span class="error"><?php echo $emailErr; ?></span>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" required><?php echo $message; ?></textarea>
                <span class="error"><?php echo $messageErr; ?></span>
            </div>
            <button type="submit">Send Message</button>
        </form>
    </div>
</body>
</html>