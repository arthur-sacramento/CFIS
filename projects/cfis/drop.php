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

            // Send the file to the server using AJAX or any preferred method
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '?action=upload', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log('File uploaded successfully.');
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    $tempFilePath = $file['tmp_name'];
    $originalFileName = $file['name'];

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

    // Optionally, you can echo a response to acknowledge the successful upload
    echo 'File uploaded successfully.';
}
?>
</body>
</html>
