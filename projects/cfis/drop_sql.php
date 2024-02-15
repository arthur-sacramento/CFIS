<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload with Hashed Filename</title>
</head>
<body>

<div id="drop-area" style="padding: 20px; border: 2px dashed #ccc;">
    <h3>Drag & Drop a File or Click to Upload</h3>
    <input type="file" id="fileInput" style="display: none;">
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var dropArea = document.getElementById('drop-area');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        dropArea.style.border = '2px solid #39a3ff';
    }

    function unhighlight() {
        dropArea.style.border = '2px dashed #ccc';
    }

    dropArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        var dt = e.dataTransfer;
        var files = dt.files;

        handleFiles(files);
    }

    function handleFiles(files) {
        ([...files]).forEach(uploadFile);
    }

    function uploadFile(file) {
        var reader = new FileReader();

        reader.onload = function (e) {
            var fileContent = e.target.result;

            var hash = sha256(fileContent);
            var extension = getFileExtension(file.name);

            var newFileName = hash + '.' + extension;

            var formData = new FormData();
            formData.append('file', file, newFileName);
            formData.append('originalFileName', file.name); // Add original filename to the form data

            // Send the file to the server using AJAX or any preferred method
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '?action=upload', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log('File uploaded successfully.');
                    // Redirect to the uploaded file
                    //window.location.href = 'files/' + newFileName;
                    alert('File uploaded!');
                } else {
                    console.error('File upload failed.');
                }
            };
            xhr.send(formData);
        };

        reader.readAsArrayBuffer(file);
    }

    function sha256(str) {
        // Implement your SHA-256 hashing function here
        // Example: return crypto.createHash('sha256').update(str).digest('hex');
        return str; // Replace with the actual hashing logic
    }

    function getFileExtension(filename) {
        return filename.split('.').pop();
    }
});
</script>

<?php

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

include("queries/db_connect.php");

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create files table
$sql = "CREATE TABLE IF NOT EXISTS files (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(255),
    file_size VARCHAR(20),
    date DATETIME NOT NULL,
    sha256_hash VARCHAR(64),
    user VARCHAR(50),
    filename VARCHAR(255),
    description VARCHAR(255),
    ranking VARCHAR(20),
    likes INT(6),
    dislikes INT(6),
    reports INT(6),
    mime_type VARCHAR(30)
)";

if ($conn->query($sql) === TRUE) {
    //echo "Table created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $user = $_SESSION['user'];

    $file = $_FILES['file'];

    $originalFileName = $_POST['originalFileName'];

    $tempFilePath = $file['tmp_name'];
    //$originalFileName = $file['name'];

    $currentDate = date('Y-m-d H:i:s');

    // Calculate SHA-256 hash
    $hash = hash_file('sha256', $tempFilePath);

    // Extract file extension
    $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);

    // Generate new filename using hash and original extension
    $newFileName = $hash . '.' . $extension;

    // Set the destination folder for uploaded files
    $uploadFolder = 'files/';

    // Move the file to the destination folder with the new filename
    $destination = $uploadFolder . $newFileName;
    move_uploaded_file($tempFilePath, $destination);

    // Get file size
    $fileSize = formatSizeUnits(filesize($destination));

    // Get MIME type
    $mimeType = mime_content_type($destination);

    // Insert information into the database
    $sql = "INSERT INTO files (url, file_size, date, sha256_hash, user, filename, mime_type) 
            VALUES ('$destination', '$fileSize', '$currentDate', '$hash', '$user','$originalFileName', '$mimeType')";

    if ($conn->query($sql) === TRUE) {
        echo 'File uploaded and information inserted into the database successfully.';
    } else {
        echo 'Error: ' . $sql . '<br>' . $conn->error;
    }
}

// Close the database connection
$conn->close();

?>

</body>
</html>
