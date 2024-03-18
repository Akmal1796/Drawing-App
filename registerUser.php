<?php

require_once "./conn.php";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {

    // Prepare and sanitize form data
    $name = mysqli_real_escape_string($conn, $_POST["uid"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["pwd"]);
    $passwordRepeat = mysqli_real_escape_string($conn, $_POST["pwdrepeat"]);

    // Validate and insert data into the database
    if ($password === $passwordRepeat) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
        $sql = "INSERT INTO `users`(`User_Name`, `Email`, `Password`) VALUES ('$name', '$email', '$hashedPassword')";

        if ($conn->query($sql) === TRUE) {
            header("Location: success.html");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Passwords do not match.";
    }

    $conn->close();
}
