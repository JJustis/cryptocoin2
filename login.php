<?php
session_start();
header('Content-Type: application/json');
include('db_connection.php'); // Include your database connection script

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$username = $input['username'];
$password = $input['password'];

// Check if the user exists
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['username'] = $username;
    $_SESSION['wallet'] = $user['wallet'];
    echo json_encode(['status' => 'success', 'message' => 'Login successful!', 'wallet' => $user['wallet']]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid username or password.']);
}

$stmt->close();
$conn->close();
?>
