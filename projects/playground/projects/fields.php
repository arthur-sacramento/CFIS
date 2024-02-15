<!DOCTYPE html>
<html>
<head>
    <title>Dynamic Text Fields</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        form {
            width: 400px;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .container-entry {
            margin-bottom: 10px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        button {
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Container Form</h1>
    <form id="containerForm" action='fields.php' method='POST'>
        <div id="containerFields">
            <div class="container-entry">
                <label for="title">Field: (e.g: name, title, URL, link, date...) </label>
                <input type="text" name="title[]" required>
                <label for="contents">Contents:</label>
                <textarea name="contents[]" required></textarea>
                <button type="button" onclick="removeContainer(this)">Remove</button>
            </div>
        </div>
        <button type="button" onclick="addContainer()">Add</button>
        <button type="submit">Save Containers</button>
    </form>

    <script>
        function addContainer() {
            const containerFields = document.getElementById('containerFields');

            const containerEntry = document.createElement('div');
            containerEntry.classList.add('container-entry');

            const titleLabel = document.createElement('label');
            titleLabel.textContent = 'Field:';
            const titleInput = document.createElement('input');
            titleInput.type = 'text';
            titleInput.name = 'title[]';
            titleInput.required = true;

            const contentsLabel = document.createElement('label');
            contentsLabel.textContent = 'Contents:';
            const contentsTextarea = document.createElement('textarea');
            contentsTextarea.name = 'contents[]';
            contentsTextarea.required = true;

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.textContent = 'Remove';
            removeButton.onclick = function() {
                removeContainer(containerEntry);
            };

            containerEntry.appendChild(titleLabel);
            containerEntry.appendChild(titleInput);
            containerEntry.appendChild(contentsLabel);
            containerEntry.appendChild(contentsTextarea);
            containerEntry.appendChild(removeButton);

            containerFields.appendChild(containerEntry);
        }

        function removeContainer(containerEntry) {
            const containerFields = document.getElementById('containerFields');
            containerFields.removeChild(containerEntry);
        }
    </script>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titles = $_POST['title'];
    $contents = $_POST['contents'];

    // Ensure the "contents" folder exists. If not, create it.
    if (!is_dir('categories/paste')) {
        mkdir('categories/paste');
    }

    // Loop through the submitted data
    for ($i = 0; $i < count($titles); $i++) {
        $title = $titles[$i];
        $content = $contents[$i];
        $item = $item . $titles[$i] . ":" . $contents[$i] . "</br>";       
    }

 // Generate a SHA1 name for the contents

        $sha1Name = sha1($item);

        // Create the file path with the "contents" folder and the SHA1 name
        $filePath = 'categories/paste/' . $sha1Name . '.html';

        // Save the contents in the file
        file_put_contents($filePath, $item);

        // Here, you can do additional processing, such as saving the title and SHA1 name in a database.
        // For demonstration purposes, let's just display the saved information.
        echo "<p>Container $sha1Name saved successfully!</p>";
        echo "<p>File Path: <a href='$filePath' target='_blank'>$filePath</a></p>";
}
?>

</body>
</html>