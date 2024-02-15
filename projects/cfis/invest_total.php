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

// Create a new table to store the results
$sqlCreateTable = "CREATE TABLE IF NOT EXISTS file_totals (
    filehash VARCHAR(255) PRIMARY KEY,
    total_value FLOAT NOT NULL
)";
$conn->query($sqlCreateTable);

// Insert the summed values into the new table
$sqlInsertValues = "INSERT INTO file_totals (filehash, total_value)
                    SELECT filehash, SUM(value) AS total_value
                    FROM transactions
                    GROUP BY filehash";

$conn->query($sqlInsertValues);

// Close the connection
$conn->close();
?>