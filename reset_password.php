<?php
// Step 1: Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 2: Validate the input
    if (empty($_POST['email']) || empty($_POST['new_password']) || empty($_POST['confirm_password'])) {
        die("All fields are required.");
    }

    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Step 3: Check if the email exists in the database
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = '';
    $db_name = 'draw_app';

    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Replace 'users' with your table name where user information is stored
    $query = "SELECT * FROM users WHERE Email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        die("Email not found.");
    }

    // Step 4: Check if the new password and confirm password match
    if ($new_password !== $confirm_password) {
        die("Passwords do not match.");
    }

    // Step 5: Reset the user's password in the database
    // Replace 'users' with your table name and 'password' with the actual column name for passwords
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET Password = ? WHERE Email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $hashed_password, $email);
    $stmt->execute();

    // Step 6: Display a success message to the user
    echo "Password reset successful!";
}
