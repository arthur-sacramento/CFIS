<?php

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cfis";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create a ZipArchive object
$zip = new ZipArchive();
$zipFileName = 'database_contents.zip';

if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
    // Get a list of tables in the database
    $tablesResult = $conn->query("SHOW TABLES");
    while ($table = $tablesResult->fetch_row()) {
        $tableName = $table[0];

        // Fetch all rows from the table
        $result = $conn->query("SELECT * FROM $tableName");
        $rows = $result->fetch_all(MYSQLI_ASSOC);

        // Convert table data to JSON
        $jsonData = json_encode($rows, JSON_PRETTY_PRINT);

        // Add JSON data to the zip file with the table name as the filename
        $zip->addFromString("$tableName.json", $jsonData);
    }

    // Close the zip file
    $zip->close();

    // Set headers to force download
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
    header('Content-Length: ' . filesize($zipFileName));
    header("Pragma: no-cache");
    header("Expires: 0");

    // Output the zip file to the browser
    readfile($zipFileName);

    // Delete the temporary zip file
    unlink($zipFileName);
} else {
    echo "Failed to create zip file";
}

// Close the database connection
$conn->close();

?>
