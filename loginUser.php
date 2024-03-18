<?php
session_start();
require_once './conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["pwd"];

    $sql = "SELECT `Password` FROM users WHERE Email='$email'";
    $result = $conn->query($sql);

    // Prepare and execute a query
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row["Password"];

        // Verify the provided password against the hashed password
        if (password_verify($password, $hashedPassword)) {
            // Successful login
            $sql = "SELECT `User_Name` FROM `users` WHERE Email = '$email'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $name = $row["User_Name"];
                $_SESSION['Email'] = $name;
            }
            header("Location: index.php?login=success");
        } else {
            // Invalid password
            echo "Invalid email or password";
        }
    } else {
        // User not found
        echo "User not found";
    }

    $conn->close();
}
