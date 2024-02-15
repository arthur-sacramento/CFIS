<!DOCTYPE html>
<html>
<head>
    <title>File Upload via Drag and Drop</title>
</head>
<body>
    <div id="dropArea" style="border: 2px dashed #ccc; padding: 20px; text-align: center;">
        <p>Drop files here to upload</p>
    </div>

    <script>
        // Handle the drop event
        function handleDrop(event) {
            event.preventDefault();
            const files = event.dataTransfer.files;
            uploadFiles(files);
        }

        // Prevent default dragover behavior
        function handleDragOver(event) {
            event.preventDefault();
        }

        // Init the event listeners
        document.getElementById("dropArea").addEventListener("drop", handleDrop, false);
        document.getElementById("dropArea").addEventListener("dragover", handleDragOver, false);

        // Function to upload files using AJAX
        function uploadFiles(files) {
            const formData = new FormData();
            for (let i = 0; i < files.length; i++) {
                formData.append("file[]", files[i]);
            }

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "<?php echo $_SERVER['PHP_SELF']; ?>", true);

            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert("Files uploaded successfully!");
                } else {
                    alert("Error uploading files. Please try again.");
                }
            };

            xhr.send(formData);
        }
    </script>

    <?php
    // Check if files were uploaded successfully
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES['file']) && !empty($_FILES['file'])) {
        $uploadDir = 'categories/upload/';

        // Create the 'upload' folder if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uploadedFiles = $_FILES['file'];

        foreach ($uploadedFiles['tmp_name'] as $key => $tmp_name) {
            $filename = $uploadDir . $uploadedFiles['name'][$key];

            // Move the file from the temporary directory to the upload directory
            if (move_uploaded_file($tmp_name, $filename)) {
                // File uploaded successfully
            } else {
                // Error handling, file not uploaded
            }
        }
    }
    ?>
</body>
</html>
