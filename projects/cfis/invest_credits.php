<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cfis";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming $userId is the user ID stored in the session variable
$username = $_SESSION['user'];

$fileHash = $_POST['filehash'];

// Validate and sanitize input (you should implement more thorough validation)
$investAmount = (int)$_POST['investAmount'];

// Retrieve user data to check credits
$sql = "SELECT * FROM users WHERE username = $username";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $userData = $result->fetch_assoc();
    
    // Check if the user has sufficient credits
    if ($userData['credits'] >= $investAmount) {
        $currentDate = date('Y-m-d H:i:s');

        // Subtract the invested amount from the 'credits' field
        $newCredits = $userData['credits'] - $investAmount;
        $updateCreditsSql = "UPDATE users SET credits = $newCredits WHERE username = $username";
        $conn->query($updateCreditsSql);

        // Insert a new record into 'files_invest' table
        //$filehash = "your_generated_file_hash"; // Replace with actual file hash
        $insertInvestSql = "INSERT INTO transactions (user, filehash, value, date) VALUES ('$username', '$fileHash', '$investAmount', '$currentDate')";
        $conn->query($insertInvestSql);

        echo "Credits invested successfully";
    } else {
        echo "Insufficient credits";
    }
} else {
    echo "Error retrieving user information";
}

// Close the database connection
$conn->close();
?>
