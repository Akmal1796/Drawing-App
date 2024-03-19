<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['Email'])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.html"); // Assuming login.php is your login page
    exit();
}

// Assuming your MySQL database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "draw_app";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user ID from session
    $user_id = $_SESSION['User_ID'];

    // Get form data
    $project_name = $_POST["projectName"];
    $project_file = $_POST["canvasImageData"];

    // Prepare and bind parameters
    $stmt = $conn->prepare("INSERT INTO works (User_ID, Project_Name, Project_File, Created_At, Updated_At) VALUES (?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("iss", $user_id, $project_name, $project_file);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Project submitted successfully!";
    } else {
        echo "Error submitting project: " . $conn->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
