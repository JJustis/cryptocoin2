<?php
// send_message.php

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

// Handle POST request to send an encrypted message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $sender_id = intval($input['sender_id']);
    $recipient_id = intval($input['recipient_id']);
    $sender_username = $input['sender_username'];
    $recipient_username = $input['recipient_username'];
    $encrypted_message = $input['message']; // Encrypted message content

    if (!empty($sender_id) && !empty($recipient_id) && !empty($encrypted_message)) {
        // Insert the message into the `messages` table
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, recipient_id, sender_username, recipient_username, encrypted_message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $sender_id, $recipient_id, $sender_username, $recipient_username, $encrypted_message);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Message sent successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to send message: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid input data."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

$conn->close();
?>
