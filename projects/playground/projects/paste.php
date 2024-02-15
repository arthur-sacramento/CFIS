<!DOCTYPE html>
<html>
<head>
    <title>Content Saver</title>
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
    <h1>Content Saver</h1>
    <form method="post">
        <div class="container-entry">
            <label for="content">Paste your content here:</label>
            <textarea name="content" id="content" rows="8" required></textarea>
        </div>
        <button type="submit">Save Content</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $content = $_POST['content'];
        $filename = 'categories/paste/' . uniqid('') . '.txt'; // Generate a unique filename

        if (file_put_contents($filename, $content)) {
            echo "<p>Content saved successfully in <a href='$filename' target='_blank'>$filename</a></p>";
        } else {
            echo '<p>Failed to save content. Please try again.</p>';
        }
    }
    ?>
</body>
</html>