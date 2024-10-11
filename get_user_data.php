<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging (remove or comment out in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection parameters (update these as necessary)
$servername = "localhost";
$dbusername = "root";          // Replace with your database username
$dbpassword = "";              // Replace with your database password
$dbname = "crypto_database";   // Change to the correct database name

// Connect to the database
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check database connection
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

// Check if user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Fetch user data
    $stmtUser = $conn->prepare("SELECT username, email, wallet_address FROM users WHERE username = ?");
    if (!$stmtUser) {
        echo json_encode(["status" => "error", "message" => "User data query failed: " . $conn->error]);
        exit();
    }
    $stmtUser->bind_param("s", $username);
    $stmtUser->execute();
    $userData = $stmtUser->get_result()->fetch_assoc();
    $stmtUser->close();

    if (!$userData) {
        echo json_encode(["status" => "error", "message" => "User not found."]);
        exit();
    }

    // Fetch transaction history for the user
    $stmtTransactions = $conn->prepare("SELECT * FROM transactions WHERE sender_address = ? OR receiver_address = ?");
    if (!$stmtTransactions) {
        echo json_encode(["status" => "error", "message" => "Transactions query failed: " . $conn->error]);
        exit();
    }
    $walletAddress = $userData['wallet_address'];
    $stmtTransactions->bind_param("ss", $walletAddress, $walletAddress);
    $stmtTransactions->execute();
    $transactions = $stmtTransactions->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmtTransactions->close();

    // Return user data and transaction history
    echo json_encode(["status" => "success", "user" => $userData, "transactions" => $transactions]);
} else {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
}

$conn->close();
?>
