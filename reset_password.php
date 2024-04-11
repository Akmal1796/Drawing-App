<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['email']) || empty($_POST['new_password']) || empty($_POST['confirm_password'])) {
        die("All fields are required.");
    }

    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    //Check if the email exists in the database
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = '';
    $db_name = 'draw_app';

    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT * FROM users WHERE Email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        die("Email not found.");
    }

    // Check if the new password and confirm password match
    if ($new_password !== $confirm_password) {
        die("Passwords do not match.");
    }

    // Reset the user's password in the database
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET Password = ? WHERE Email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $hashed_password, $email);
    $stmt->execute();

    // Display a success message to the user
    echo "Password reset successful!";
}
