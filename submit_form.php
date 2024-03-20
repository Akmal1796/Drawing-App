<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['Email'])) {
    // Redirect the user to the login page or display an error message
    header("Location: login.html");
    exit();
}

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
        // Redirect to index.php with success message
        header("Location: index.php?submit_success=1");
        exit();
    } else {
        // Redirect to index.php with error message
        header("Location: index.php?submit_error=" . urlencode("Error submitting project: " . $conn->error));
        exit();
    }


    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
