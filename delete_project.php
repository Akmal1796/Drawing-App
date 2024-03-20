<?php
session_start();
require_once './conn.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['User_ID'])) {
    // Redirect the user to the login page or display an error message
    echo "User not logged in";
    exit();
}

// Check if the project ID is provided in the request
if (!isset($_GET['id'])) {
    echo "Project ID is required";
    exit();
}

$projectId = $_GET['id'];

// Delete the project from the database
$sql = "DELETE FROM works WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $projectId);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Project deleted successfully";
} else {
    echo "Failed to delete project";
}

$stmt->close();
$conn->close();
