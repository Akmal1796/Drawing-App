<?php
session_start();
require_once './conn.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['User_ID'])) {
    // Redirect the user to the login page or display an error message
    echo "User not logged in";
    exit();
}

$user_id = $_SESSION['User_ID'];

// Fetch projects associated with the current user ID from the database
$sql = "SELECT ID, Project_Name, Project_File FROM works WHERE User_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch and output projects as JSON
    $projects = array();
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
    echo json_encode($projects);
} else {
    echo "No projects found for the user";
}

$stmt->close();
$conn->close();
