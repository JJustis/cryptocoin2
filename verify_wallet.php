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

// Handle POST request for wallet verification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input
    $walletAddress = trim($input['walletAddress'] ?? '');
    $hmacSecret = trim($input['hmacSecret'] ?? '');

    if (empty($walletAddress) || empty($hmacSecret)) {
        echo json_encode(["status" => "error", "message" => "Invalid wallet address or HMAC secret."]);
        exit();
    }

    // Retrieve wallet details
    $query = "SELECT user_id, hmac_secret FROM wallets WHERE wallet_address = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $walletAddress);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Wallet not found."]);
        $stmt->close();
        exit();
    }
    
    $walletData = $result->fetch_assoc();
    $stmt->close();

    // Calculate and verify the HMAC secret
    $calculatedHMAC = hash_hmac('sha256', $walletAddress, $hmacSecret);
	echo "Stored HMAC: " . $walletData['hmac_secret'] . "\n";
echo "Calculated HMAC: " . $calculatedHMAC . "\n";
    if (hash_equals($walletData['hmac_secret'], $calculatedHMAC)) {
        // Correct HMAC, log in user
        $userId = $walletData['user_id'];

        // Retrieve username associated with the user ID
        $userQuery = "SELECT username FROM users WHERE user_id = ?";
        $userStmt = $conn->prepare($userQuery);
        $userStmt->bind_param("i", $userId);
        $userStmt->execute();
        $userResult = $userStmt->get_result();

        if ($userResult->num_rows > 0) {
            $username = $userResult->fetch_assoc()['username'];

            // Store username in session and send success response
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $userId;  // Store user ID in session as well
            echo json_encode(["status" => "success", "message" => "Wallet linked successfully!", "username" => $username]);
        } else {
            echo json_encode(["status" => "error", "message" => "User associated with wallet not found."]);
        }
        $userStmt->close();
    } else {
        // HMAC does not match
        echo json_encode(["status" => "error", "message" => "Invalid HMAC secret."]);
    }
} else {
    // Invalid request method
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

$conn->close();
?>
