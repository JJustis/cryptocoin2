<?php
// Start the session and set the response type to JSON
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection parameters (update as needed)
$servername = "localhost";
$dbusername = "root";  // Replace with your database username
$dbpassword = "";      // Replace with your database password
$dbname = "crypto_database";  // Replace with your database name

// Connect to the database
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check the database connection
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// SQL query to get the latest block's hash
$sql = "SELECT `hash` FROM `blocks` ORDER BY `id` DESC LIMIT 1";
$result = $conn->query($sql);

// Check if any results were returned
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['status' => 'success', 'hash' => $row['hash']]);
} else {
    // No blocks found, return an error or a default message
    echo json_encode(['status' => 'error', 'message' => 'No blocks found in the blockchain.']);
}

// Close the database connection
$conn->close();
?>
