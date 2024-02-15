<?php

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Sacrament";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    mail VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    files VARCHAR(255),
    credits VARCHAR(20),
    ranking VARCHAR(20),
    likes INT(6),
    dislikes INT(6),
    reports INT(6),
    btc VARCHAR(50),
    pix VARCHAR(50),
    paypal VARCHAR(255)
)";

if ($conn->query($sql) === TRUE) {
    //echo "Table created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Handle user registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $mail = $_POST["mail"];
    $phone = $_POST["phone"];
    $btc = $_POST["btc"];
    $pix = $_POST["pix"];
    $paypal = $_POST["paypal"];

    $insertSql = "INSERT INTO users (username, password, mail, phone, btc, pix, paypal) 
                  VALUES ('$username', '$password', '$mail', '$phone', '$btc', '$pix', '$paypal')";

    if ($conn->query($insertSql) === TRUE) {
        echo "User registered successfully<br>";
    } else {
        echo "Error registering user: " . $conn->error . "<br>";
    }
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $username = $_POST["login_username"];
    $password = $_POST["login_password"];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row["password"];

        if (password_verify($password, $hashedPassword)) {
            echo "Login successful!<br>";
        } else {
            echo "Invalid password<br>";
        }
    } else {
        echo "User not found<br>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: space-between;
        }

        .registration-container,
        .login-container {
            width: 45%; /* Adjust the width as needed */
            margin: 5% 2%;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
        }

        input {
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <h2>User Registration</h2>
        <form action="" method="post">
            <!-- Registration form fields here -->
            <label for="username">Username:</label>
            <input type="text" name="username" required><br>

            <label for="password">Password:</label>
            <input type="password" name="password" required><br>

            <label for="mail">Email:</label>
            <input type="email" name="mail" required><br>

            <label for="phone">Phone:</label>
            <input type="text" name="phone"><br>

            <label for="btc">BTC:</label>
            <input type="text" name="btc"><br>

            <label for="pix">PIX:</label>
            <input type="text" name="pix"><br>

            <label for="paypal">PayPal:</label>
            <input type="text" name="paypal"><br>

            <input type="submit" name="register" value="Register">
        </form>
    </div>

    <div class="login-container">
        <h2>User Login</h2>
        <form action="" method="post">
            <!-- Login form fields here -->
            <label for="login_username">Username:</label>
            <input type="text" name="login_username" required><br>

            <label for="login_password">Password:</label>
            <input type="password" name="login_password" required><br>

            <input type="submit" name="login" value="Login">
        </form>
    </div>
</body>
</html>