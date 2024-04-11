<?php
session_start();
require_once './conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["pwd"];

    // Prepare and execute a query to fetch user data including the user ID
    $sql = "SELECT `ID`, `Password`, `User_Name` FROM users WHERE Email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row["ID"]; // Retrieve user ID from the fetched row
        $hashedPassword = $row["Password"];

        // Verify the provided password against the hashed password
        if (password_verify($password, $hashedPassword)) {
            // Successful login
            $name = $row["User_Name"];
            $_SESSION['User_ID'] = $user_id; // Set user ID in session
            $_SESSION['Email'] = $name; // Set user name in session

            header("Location: index.php?login=success");
            exit();
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
