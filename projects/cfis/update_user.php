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
$user = $_SESSION['user'];

// Validate and sanitize input (you should implement more thorough validation)
$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$files = mysqli_real_escape_string($conn, $_POST['files']);
$credits = mysqli_real_escape_string($conn, $_POST['credits']);
$ranking = mysqli_real_escape_string($conn, $_POST['ranking']);
$likes = (int)$_POST['likes'];
$dislikes = (int)$_POST['dislikes'];
$reports = (int)$_POST['reports'];
$btc = mysqli_real_escape_string($conn, $_POST['btc']);
$pix = mysqli_real_escape_string($conn, $_POST['pix']);
$paypal = mysqli_real_escape_string($conn, $_POST['paypal']);


// Update user information
$sql = "UPDATE users SET 
        password = '$password',
        mail = '$email',
        phone = '$phone',       
        btc = '$btc',
        pix = '$pix',
        paypal = '$paypal'
        WHERE username = $user";

/*/
// Full fields
$sql = "UPDATE users SET 
        username = '$username',
        password = '$password',
        mail = '$email',
        phone = '$phone',
        files = '$files',
        credits = '$credits',
        ranking = '$ranking',
        likes = $likes,
        dislikes = $dislikes,
        reports = $reports,
        btc = '$btc',
        pix = '$pix',
        paypal = '$paypal'
        WHERE username = $user";
/*/

if ($conn->query($sql) === TRUE) {
    echo "User information updated successfully. <a href='user.php'>back</a>";
} else {
    echo "Error updating user information: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
