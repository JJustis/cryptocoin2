<?php
// register_node.php

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

// Handle POST request to register a new node
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $nodeUrl = filter_var($input['nodeUrl'], FILTER_SANITIZE_URL);

    if (!empty($nodeUrl)) {
        // Insert the new node into the `nodes` table
        $stmt = $conn->prepare("INSERT INTO nodes (url) VALUES (?)");
        $stmt->bind_param("s", $nodeUrl);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Node registered successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to register node: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid node URL provided."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

$conn->close();
?>
