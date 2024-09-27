<?php
require_once 'Database.php';

class Contact {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function submitMessage($name, $email, $message) {
        if (empty($name) || empty($email) || empty($message)) {
            return "All fields are required.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format.";
        }

        $stmt = $this->db->conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);

        if ($stmt->execute()) {
            return "Message sent successfully!";
        } else {
            return "Error: " . $stmt->error;
        }
    }
}
?>
