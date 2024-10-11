<?php
// get_inbox.php

session_start();
header('Content-Type: application/json');

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crypto_database";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Fetch messages for the logged-in user
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Prepare and execute query to fetch messages for the user
    $stmt = $conn->prepare("SELECT sender_username, encrypted_message, timestamp FROM messages WHERE recipient_username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = [
            'sender' => $row['sender_username'],
            'message' => $row['encrypted_message'],
            'timestamp' => $row['timestamp']
        ];
    }

    echo json_encode(["status" => "success", "messages" => $messages]);
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
}

$conn->close();
?>
