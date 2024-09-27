<?php
require_once 'Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function signUp($first_name, $last_name, $email, $password, $confirm_password) {
        // Validate inputs
        if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password)) {
            return "All fields are required.";
        }

        if ($password !== $confirm_password) {
            return "Passwords do not match.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format.";
        }

        // Check if email already exists
        $stmt = $this->db->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return "Email is already registered.";
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $stmt = $this->db->conn->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);

        if ($stmt->execute()) {
            return "Sign-up successful!";
        } else {
            return "Error: " . $stmt->error;
        }
    }

    public function signIn($email, $password) {
        $stmt = $this->db->conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                return true;
            } else {
                return "Invalid email or password.";
            }
        } else {
            return "User not found.";
        }
    }

    public function signOut() {
        session_start();
        session_unset();
        session_destroy();
        return "You have been signed out.";
    }

    public function isAdmin($user_id) {
        $stmt = $this->db->conn->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user['role'] === 'admin';
    }
}
?>
