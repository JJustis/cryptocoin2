<?php
// get_public_key_roster.php

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

// Fetch the list of all public keys
$sql = "SELECT username, public_key FROM users";
$result = $conn->query($sql);

$publicKeys = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $publicKeys[] = [
            'username' => $row['username'],
            'publicKey' => $row['public_key']
        ];
    }
    echo json_encode(["status" => "success", "publicKeys" => $publicKeys]);
} else {
    echo json_encode(["status" => "error", "message" => "No public keys found."]);
}

$conn->close();
?>
