<?php
// Define database connection parameters
$servername = "localhost"; // Replace with your server name or IP address
$dbusername = "root";      // Replace with your database username
$dbpassword = "";          // Replace with your database password
$dbname = "crypto_database"; // Replace with your database name

// Create a new MySQLi connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
