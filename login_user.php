<?php
// Include the database connection file
include 'db_connection.php';

// Enable JSON output
header('Content-Type: application/json');

// Capture the incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Validate input
if (isset($data['username']) && isset($data['password'])) {
    $username = $data['username'];
    $password = $data['password'];

    // Prepare and execute SQL statement to retrieve user information
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if user exists and verify password
    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['wallet'] = $user['wallet'];
        echo json_encode(['status' => 'success', 'message' => 'Login successful!', 'wallet' => $user['wallet']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid username or password.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
}

$conn->close();
?>
