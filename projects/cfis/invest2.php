<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investment Form</title>
</head>
<body>
    <h2>Investment Form</h2>
    <form action="invest2.php" method="post">
        <label for="value">Value:</label>
        <input type="text" name="value" required>
        
        <label for="filehash">File Hash:</label>
        <input type="text" name="filehash" required>

        <input type="submit" value="Submit">
    </form>
</body>
</html>


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

// Assuming you have received the form data via POST
$value = $_POST['value'];
$filehash = $_POST['filehash'];

// Assuming you have a user ID for the current user (replace with your authentication logic)
$username = "1234";

// Get the current credits of the user
$sql_select_credits = "SELECT credits FROM users WHERE username = $username";
$result = $conn->query($sql_select_credits);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $current_credits = $row['credits'];

    // Check if the user has enough credits
    if ($current_credits >= $value) {
        // Subtract the 'value' from 'credits'
        $new_credits = $current_credits - $value;
        $sql_update_credits = "UPDATE users SET credits = $new_credits WHERE username = $username";
        $conn->query($sql_update_credits);

        // Insert a new record into the 'investment' table
        $current_date = date("Y-m-d");
        $sql_insert_investment = "INSERT INTO investment (user, value, filehash, date) VALUES ($username, $value, '$filehash', '$current_date')";
        $conn->query($sql_insert_investment);

        echo "Investment successful!";
    } else {
        echo "Insufficient credits.";
    }
} else {
    echo "User not found.";
}

// Close the database connection
$conn->close();
?>