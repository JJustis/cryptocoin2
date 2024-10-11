<?php
session_start();
header('Content-Type: application/json');

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crypto_database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check database connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Handle POST request to register a new user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $username = filter_var($input['username'], FILTER_SANITIZE_STRING);
    $password = $input['password'];
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Username already exists."]);
        exit();
    }
    $stmt->close();

    // Generate a unique wallet address (using a hash of the username and current time)
    $walletAddress = hash('sha256', $username . time());

    // Generate a public and private key pair for the wallet (simplified for demonstration)
    $privateKey = base64_encode(openssl_random_pseudo_bytes(32));  // 32 bytes of random data
    $publicKey = base64_encode(openssl_pkey_get_details(openssl_pkey_new())['key']);  // Random public key

    // Hash and salt the password before storing
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Insert the new user into the database
    $stmt = $conn->prepare("INSERT INTO users (username, password_hash, email, wallet_address, public_key) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $passwordHash, $email, $walletAddress, $publicKey);

        if ($stmt->execute()) {
        $user_id = $stmt->insert_id;  // Get the ID of the newly inserted user

        

        // Insert the wallet data into the wallets table
        $walletStmt = $conn->prepare("INSERT INTO wallets (wallet_address, balance, user_id) VALUES (?, 0, ?)");
        $walletStmt->bind_param("si", $walletAddress, $user_id);

        if ($walletStmt->execute()) {
            echo json_encode(["status" => "success", "walletAddress" => $walletAddress]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to create wallet."]);
        }

        $walletStmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to register user."]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

$conn->close();
?>
