<?php
header('Content-Type: application/json');

// Database connection parameters
$servername = "localhost";
$dbusername = "root";  // Replace with your database username
$dbpassword = "";      // Replace with your database password
$dbname = "crypto_database";  // Replace with your database name

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

// Retrieve pending transactions from the database
$sql = "SELECT * FROM transactions WHERE status = 'pending'";
$result = $conn->query($sql);

$transactions = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $transactions[] = [
            'id' => $row['id'],
            'sender' => $row['sender'],
            'recipient' => $row['recipient'],
            'amount' => $row['amount'],
            'timestamp' => $row['timestamp']
        ];
    }

    echo json_encode([
        "status" => "success",
        "transactions" => $transactions
    ]);
} else {
    echo json_encode([
        "status" => "success",
        "transactions" => []  // Return an empty array if no pending transactions found
    ]);
}

$conn->close();
?>
