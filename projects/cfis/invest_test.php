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

$checkTableQuery = "SHOW TABLES LIKE 'transactions'";
$tableExists = $conn->query($checkTableQuery);

if ($tableExists->num_rows == 0) {
    // 'transactions' table does not exist, create it
    $createTableQuery = "
        CREATE TABLE transactions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user VARCHAR(50) NOT NULL,
            value FLOAT NOT NULL,
            filehash VARCHAR(255) NOT NULL,
            date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";
    $conn->query($createTableQuery);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you have a session or some way to identify the user
    $username = $_SESSION['user']; // Replace with actual username or user ID

    // Sanitize and validate input
    $value = filter_input(INPUT_POST, 'value', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $filehash = filter_input(INPUT_POST, 'filehash', FILTER_SANITIZE_STRING);

    // Check if the user has sufficient credits
    $checkCreditsQuery = "SELECT credits FROM users WHERE username = '$username'";
    $result = $conn->query($checkCreditsQuery);

    if ($result && $result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        $userCredits = floatval($userData['credits']);

        if ($userCredits >= $value) {
            // Update 'users' table by subtracting the credits
            $updateCreditsQuery = "UPDATE users SET credits = credits - $value WHERE username = '$username'";
            $conn->query($updateCreditsQuery);

            // Insert transaction into 'transactions' table
            $insertTransactionQuery = "INSERT INTO transactions (user, value, filehash, date) VALUES ('$username', $value, '$filehash', NOW())";
            $conn->query($insertTransactionQuery);

            echo "Transaction successful!";
        } else {
            echo "Insufficient credits.";
        }
    } else {
        echo "User not found.";
    }
}

// HTML Form
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Form</title>
</head>
<body>
    <h2>Transaction Form</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="value">Value:</label>
        <input type="text" name="value" required>

        <label for="filehash">File Hash:</label>
        <input type="text" name="filehash" required>

        <input type="submit" value="Submit">
    </form>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>