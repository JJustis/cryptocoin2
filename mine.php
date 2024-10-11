<?php
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection parameters
$servername = "localhost";
$dbusername = "root";           // Replace with your database username
$dbpassword = "";               // Replace with your database password
$dbname = "crypto_database";     // Replace with your database name

// Create database connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check database connection
if ($conn->connect_error) {
    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed: " . $conn->connect_error
    ]);
    exit();
}

// Retrieve the mined block from the POST request
$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['minedBlock'])) {
    echo json_encode([
        "status" => "error",
        "message" => "No block data provided."
    ]);
    exit();
}

$minedBlock = $input['minedBlock'];

// Insert the new block into the blockchain
$stmt = $conn->prepare("INSERT INTO blocks (previous_hash, transactions, timestamp, nonce, hash, reward_address) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param(
    "sssiss",
    $minedBlock['previousHash'],
    json_encode($minedBlock['transactions']),
    $minedBlock['timestamp'],
    $minedBlock['nonce'],
    $minedBlock['hash'],
    $minedBlock['rewardAddress'] ?? null
);

if ($stmt->execute()) {
    // Mark the mined transactions as 'confirmed'
    $transactionIds = array_map(function($txn) { return $txn['id']; }, $minedBlock['transactions']);
    if (!empty($transactionIds)) {
        $ids = implode(',', $transactionIds);
        $conn->query("UPDATE transactions SET status = 'confirmed' WHERE id IN ($ids)");
    }

    echo json_encode([
        "status" => "success",
        "message" => "Block added successfully!"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to add block: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>

