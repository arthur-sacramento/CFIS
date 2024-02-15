<?php

session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cfis";

// Create a connection
$conn = new mysqli($servername, $username, $password);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db($dbname);

// Create the table if it doesn't exist
// Create files table
$sql = "CREATE TABLE IF NOT EXISTS file_information (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(255) NOT NULL,
    file_size VARCHAR(20) NOT NULL,
    date DATETIME NOT NULL,
    sha256_hash VARCHAR(64) NOT NULL,
    user VARCHAR(50),
    filename VARCHAR(255),
    description VARCHAR(255),
    connection_delay FLOAT(10,4) NOT NULL,
    mime_type VARCHAR(255)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the URL from the form
    $url = $_POST['url'];

    // Record the start time to measure connection delay
    $startTime = microtime(true);

    // Download the file
    $fileContents = file_get_contents($url);

    // Record the end time
    $endTime = microtime(true);

    if ($fileContents !== false) {
        // Generate a temporary file name
        $tempFileName = tempnam(sys_get_temp_dir(), 'downloaded_file_');

        // Save the file contents to the temporary file
        file_put_contents($tempFileName, $fileContents);

        // Get mime type
        $mimeType = getMimeTypeFromContents($fileContents);

        // Get file size
        $fileSize = formatSizeUnits(filesize($tempFileName));

        // Get current date
        $currentDate = date('Y-m-d H:i:s');

        // Calculate the SHA-256 hash of the downloaded file
        $hash = calculateSha256($tempFileName);

        // Extract file extension from the URL
        $pathInfo = pathinfo($url);
        $extension = isset($pathInfo['extension']) ? $pathInfo['extension'] : '';

        // Generate a new filename using the hash and extension
        $newFileName = $hash . '.' . $extension;

        // Set the destination folder for downloaded files
        $destinationFolder = 'files/';

        // Move the file to the destination folder with the new filename
        $destination = $destinationFolder . $newFileName;
        rename($tempFileName, $destination);

        // Calculate the delay to establish the connection
        $connectionDelay = round($endTime - $startTime, 4);

        // Insert data into the database
        $sql = "INSERT INTO file_information (url, file_size, date, sha256_hash, connection_delay, mime_type, filename)
                VALUES ('$url', '$fileSize', '$currentDate', '$hash', '$connectionDelay', '$mimeType', '$newFileName')";
        if ($conn->query($sql) === TRUE) {
            echo "File information saved to the database successfully<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
        }
    } else {
        echo "Failed to download the file.";
    }
}
// Close the connection
$conn->close();

// Function to calculate SHA-256 hash of a file
function calculateSha256($file)
{
    return hash_file('sha256', $file);
}

// Function to get file size in a human-readable format
function formatSizeUnits($bytes)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }

    return round($bytes, 2) . ' ' . $units[$i];
}

function getMimeTypeFromContents($fileContents) {
    // Create a Fileinfo resource
    $finfo = finfo_open(FILEINFO_MIME_TYPE);

    // Get MIME type from the file contents
    $mime_type = finfo_buffer($finfo, $fileContents);

    // Close the Fileinfo resource
    finfo_close($finfo);

    return $mime_type;
}

function getMimeTypeFromUrl($url) {
    // Get headers from the URL
    $headers = get_headers($url, 1);

    // Check if the "Content-Type" header is present
    if (isset($headers['Content-Type'])) {
        // Extract and return the MIME type
        return $headers['Content-Type'];
    } else {
        // If "Content-Type" header is not present, return null
        return null;
    }
}

function userIP() {
    // Check if the IP is from a shared client
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    // Check if the IP is from a proxy
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    // If neither is available, use the remote address
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}

function serverIP() {
    // Get the server's IP address
    $ip = $_SERVER['SERVER_ADDR'];

    return $ip;
}

echo $userIP = userIP();
echo $serverIP = serverIP();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Information and Connection Delay</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
        }

        h1 {
            color: #333;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        p {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>File Information and Connection Delay</h1>
    <form method="post" action="">
        <label for="url">Enter URL:</label>
        <input type="url" name="url" id="url" required>
        <button type="submit">Get Information and Save to Database</button>
    </form>
</body>
</html>
