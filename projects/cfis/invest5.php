<!DOCTYPE html>
<html>
<head>
  <title>Subtract Credits</title>
</head>
<body>
  <form action="subtract_credits.php" method="post">
    <label for="user_id">User ID:</label>
    <input type="text" name="user_id">
    <br>
    <label for="credits">Credits to Subtract:</label>
    <input type="number" name="credits">
    <br>
    <label for="hash">Transaction Hash:</label>
    <input type="text" name="hash">
    <br>
    <input type="submit" value="Subtract Credits">
  </form>
</body>
</html>

<?php
session_start();

// Connect to MySQL database
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "credits";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get user ID, credits to subtract, and transaction hash from form
$user_id = $_POST['user_id'];
$credits = $_POST['credits'];
$hash = $_POST['hash'];

// Check if user ID is valid
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
  // User ID is not valid
  echo "Invalid user ID.";
} else {
  // User ID is valid

  // Check if credits to subtract is valid
  if ($credits <= 0) {
    // Credits to subtract is not valid
    echo "Invalid credits to subtract.";
  } else {
    // Credits to subtract is valid

    // Check if user has enough credits
    $sql = "SELECT credits FROM users WHERE id='$user_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $current_credits = $row['credits'];

    if ($current_credits - $credits < 0) {
      // User does not have enough credits
      echo "Insufficient credits.";
    } else {
      // User has enough credits

      // Subtract credits from user
      $sql = "UPDATE users SET credits=credits-$credits WHERE id='$user_id'";
      $result = $conn->query($sql);

      // Insert transaction log entry
      $sql = "INSERT INTO transaction_log (user_id, credits, hash) VALUES ('$user_id', -$credits, '$hash')";
      $result = $conn->query($sql);

      // Redirect to success page
      header("Location: success.php");
    }
  }
}

// Close database connection
$conn->close();
?>

**MySQL:**

```sql
CREATE TABLE users (
  id INT NOT NULL AUTO_INCREMENT,
  credits INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
);

CREATE TABLE transaction_log (
  id INT NOT NULL AUTO_INCREMENT,
  user_id INT NOT NULL,
  credits INT NOT NULL,
  hash VARCHAR(255) NOT NULL,
  timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);