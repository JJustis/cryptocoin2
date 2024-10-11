<?php
// This script initializes the genesis block in the blockchain

header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection parameters
$servername = "localhost";
$dbusername = "root";          // Replace with your database username
$dbpassword = "";              // Replace with your database password
$dbname = "crypto_database";    // Replace with your database name

// Create database connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check database connection
if ($conn->connect_error) {
    die(json_encode([
        "status" => "error",
        "message" => "Database connection failed: " . $conn->connect_error
    ]));
}

// Check if the genesis block already exists
$checkQuery = "SELECT * FROM blocks WHERE previous_hash = '0x0000000000000000000000000000000000000000000000000000000000000000'";
$result = $conn->query($checkQuery);
if ($result->num_rows > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Genesis block already exists."
    ]);
    exit();
}

// Create the genesis block
$genesisBlock = [
    'previous_hash' => '0x0000000000000000000000000000000000000000000000000000000000000000',
    'transactions' => json_encode([]),  // Empty transactions
    'timestamp' => date('Y-m-d H:i:s'),
    'nonce' => 0,
    'hash' => ''  // To be calculated below
];

// Calculate the hash of the genesis block
$blockString = $genesisBlock['previous_hash'] . $genesisBlock['transactions'] . $genesisBlock['timestamp'] . $genesisBlock['nonce'];
$genesisBlock['hash'] = hash('sha256', $blockString);

// Insert the genesis block into the database
$stmt = $conn->prepare("INSERT INTO blocks (previous_hash, transactions, timestamp, nonce, hash) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param(
    "sssis",
    $genesisBlock['previous_hash'],
    $genesisBlock['transactions'],
    $genesisBlock['timestamp'],
    $genesisBlock['nonce'],
    $genesisBlock['hash']
);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Genesis block created successfully.",
        "block" => $genesisBlock
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to create genesis block: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
