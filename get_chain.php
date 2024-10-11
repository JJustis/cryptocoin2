<?php
header('Content-Type: application/json');

// Database connection parameters
$servername = "localhost";
$dbusername = "root";          // Replace with your database username
$dbpassword = "";              // Replace with your database password
$dbname = "crypto_database";    // Replace with your database name

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

// Query to get all blocks ordered by their ID (ascending)
$sql = "SELECT * FROM blocks ORDER BY id ASC";
$result = $conn->query($sql);

$blockchain = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Add each block's data to the blockchain array
        $blockchain[] = [
            'id' => $row['id'],
            'previous_hash' => $row['previous_hash'],
            'transactions' => json_decode($row['transactions'], true),  // Decode JSON transactions
            'timestamp' => $row['timestamp'],
            'nonce' => $row['nonce'],
            'hash' => $row['hash'],
            'reward_address' => $row['reward_address']
        ];
    }

    // Return the entire blockchain as a JSON response
    echo json_encode([
        "status" => "success",
        "chain" => $blockchain
    ]);
} else {
    // No blocks found in the blockchain
    echo json_encode([
        "status" => "success",
        "chain" => []
    ]);
}

$conn->close();
?>
