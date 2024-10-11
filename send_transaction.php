<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection parameters
$servername = "localhost";
$dbusername = "root";          // Replace with your database username
$dbpassword = "";              // Replace with your database password
$dbname = "crypto_database";   // Replace with your actual database name

// Connect to the database
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check database connection
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

// Handle POST request for sending a transaction
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $sender_wallet = $input['sender_wallet'] ?? '';
    $recipient_wallet = $input['recipient_wallet'] ?? '';
    $amount = $input['amount'] ?? 0;

    if (empty($sender_wallet) || empty($recipient_wallet) || $amount <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid transaction data."]);
        exit();
    }

    // Check if sender has enough balance
    $query = "SELECT balance FROM wallets WHERE wallet_address = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $sender_wallet);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Sender wallet not found."]);
        exit();
    }
    $sender_balance = $result->fetch_assoc()['balance'];

    if ($sender_balance < $amount) {
        echo json_encode(["status" => "error", "message" => "Insufficient balance."]);
        exit();
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Deduct amount from sender
        $update_sender = "UPDATE wallets SET balance = balance - ? WHERE wallet_address = ?";
        $stmt_sender = $conn->prepare($update_sender);
        $stmt_sender->bind_param("ds", $amount, $sender_wallet);
        $stmt_sender->execute();

        // Add amount to recipient
        $update_recipient = "UPDATE wallets SET balance = balance + ? WHERE wallet_address = ?";
        $stmt_recipient = $conn->prepare($update_recipient);
        $stmt_recipient->bind_param("ds", $amount, $recipient_wallet);
        $stmt_recipient->execute();

        // Log the transaction in the transactions table
        $insert_transaction = "INSERT INTO transactions (sender_wallet, recipient_wallet, amount, timestamp) VALUES (?, ?, ?, NOW())";
        $stmt_transaction = $conn->prepare($insert_transaction);
        $stmt_transaction->bind_param("ssd", $sender_wallet, $recipient_wallet, $amount);
        $stmt_transaction->execute();

        // Commit transaction
        $conn->commit();
        echo json_encode(["status" => "success", "message" => "Transaction successful."]);
    } catch (Exception $e) {
        // Rollback transaction if any error occurs
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => "Transaction failed: " . $e->getMessage()]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

$conn->close();
?>
