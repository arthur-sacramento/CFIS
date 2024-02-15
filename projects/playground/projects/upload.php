<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Folder path from the text field
    $folderPath = $_POST['folder'];

    $specialCharacters = array ('<','>', '.', '/');        

    $folderPath = str_replace($specialCharacters, "", $folderPath);

    if ($folderPath == ""){$folderPath = 'default';} 

    if (strpos($folderPath, '.') !== false) {
        echo "Error: Dots are not allowed in the folder name.";
        die;
    }

    // Check if the folder exists, create if it doesn't
    if (!is_dir($folderPath)) {
        mkdir('categories/' . $folderPath, 0755, true);
    }

    // Process the uploaded file
    $uploadedFile = $_FILES['file'];
    $filename = basename($uploadedFile['name']);
    $destination = 'categories/' .$folderPath . '/' . $filename;

    // Check if the file already exists
    if (file_exists($destination)) {
        echo "File already exists. Please choose a different filename.";
    }
    // Check if the file is a PHP file
    elseif ($uploadedFile['type'] === 'application/x-php' || pathinfo($filename, PATHINFO_EXTENSION) === 'php') {
        echo "PHP files are not allowed.";
    }
    // Upload the file
    elseif (move_uploaded_file($uploadedFile['tmp_name'], $destination)) {
        echo "File uploaded successfully.";
    } else {
        echo "Error uploading the file.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>File Upload</title>
</head>
  <style>
body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 20px;
  background-color: #f5f5f5;
}

.container {
  max-width: 500px;
  margin: 0 auto;
  background-color: #fff;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

h1 {
  text-align: center;
  margin-top: 0;
}

form {
  margin-top: 20px;
}

label {
  display: block;
  margin-bottom: 5px;
}

input[type="text"],
input[type="file"] {
  width: 100%;
  padding: 10px;
  font-size: 16px;
  border-radius: 3px;
  border: 1px solid #ccc;
}

input[type="submit"] {
  background-color: #4caf50;
  color: #fff;
  border: none;
  padding: 10px 20px;
  font-size: 16px;
  border-radius: 3px;
  cursor: pointer;
}

input[type="submit"]:hover {
  background-color: #45a049;
}

.error {
  color: red;
  margin-top: 5px;
}

.success {
  color: green;
  margin-top: 5px;
}


  </style>
<body>
    <form method="POST" enctype="multipart/form-data">
        <label for="folder">Category:</label>
        <input type="text" name="folder" id="folder" required>
        <br><br>
        <label for="file">File:</label>
        <input type="file" name="file" id="file" required>
        <br><br>
        <input type="submit" value="Upload">
    </form>
</body>
</html>