<?php 
  error_reporting(0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Creation</title>
    <style>
        /* Reset some default styles */
        body, h1, form {
            margin: 0;
            padding: 0;
        }

        /* Page layout and styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex; /* Use flex layout */
            flex-wrap: wrap; /* Allow wrapping to the next row */
            justify-content: space-between; /* Space between columns */
        }

        label {
            font-weight: bold;
        }

        .column {
            width: calc(50% - 10px); /* Two columns with a little spacing between */
            margin-bottom: 10px;
        }

        input[type="text"],
        textarea,
        select,
        input[type="file"] {
            width: 80%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="file"] {
            padding: 6px;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Content Creation</h1>
    <form action="create.php" method="post" enctype="multipart/form-data">
        <div class="column">
            <label for="thumbnail">Thumbnail</label><br>
            <input type="file" name="thumbnail" id="thumbnail" required>
        </div>

        <div class="column">
            <label for="user">User</label><br>
            <input type="text" name="user" id="user" required>
        </div>

        <div class="column">
            <label for="title">Title</label><br>
            <input type="text" name="title" id="title" required>
        </div>

        <div class="column">
            <label for="url">Link or URL</label><br>
            <input type="text" name="url" id="url" required></textarea>
        </div>

        <div class="column">
            <label for="description">Description</label><br>
            <textarea name="description" id="description" required></textarea>
        </div>

        <div class="column">
            <label for="table">Table</label><br>
            <input type="text" name="table" id="table" required>
        </div>

 <div class="column">

    <label for="table">Style</label><br> 
    <select name="style">
        <option value="default" selected>Default</option>
        <option value="simple">Simple</option>
    </select>
 </div>
        <div class="column">
            <input type="submit" value="Submit">
        </div>
    </form>
</body>
</html>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $thumbnailFile = $_FILES["thumbnail"];
    $title = $_POST["title"];   
    $user = $_POST["user"];
    $url = $_POST["url"];
    $description = $_POST["description"];
    $table = $_POST["table"];
    $style = $_POST["style"];

    $date = date('Y-m-d');

    $avoid_js_insertion = array ('<','>');

    $avoid_directory_change = array ('.','/'); 
       
    $thumbnailFile = str_replace($avoid_js_insertion, "", $thumbnailFile); 
    $title = str_replace($avoid_js_insertion, "", $title);
    $user = str_replace($avoid_js_insertion, "", $user);
    $url = str_replace($avoid_js_insertion, "", $url);
    $description = str_replace($avoid_js_insertion, "", $description); 
    $table = str_replace($avoid_directory_change, "", $table);  

    $table = str_replace($avoid_js_insertion, "", $table);  

    // Define the target directory to save the file
    $targetDirectory = "files/";

    // Check if the target directory exists, and create it if not
    if (!file_exists($targetDirectory)) {
        mkdir($targetDirectory, 0777, true);
    }

    // Get the file extension of the uploaded thumbnail
    $thumbnailExtension = pathinfo($thumbnailFile["name"], PATHINFO_EXTENSION);

    // Generate a unique filename for the thumbnail
    //$thumbnailFileName = $table . "_thumbnail." . $thumbnailExtension;

    $thumbnailFileName = time() . "." . $thumbnailExtension;
   
    // Move the uploaded thumbnail to the target directory
    $thumbnailPath = $targetDirectory . $thumbnailFileName;
    move_uploaded_file($thumbnailFile["tmp_name"], $thumbnailPath);

  $max_table_size = 1000000;

  $x = 1;

    if ($style == "default"){
    // Create an HTML container with the provided data
    $htmlContainer = "
        <div class='container'>
            <div class='left-column'>
                <a href='thumbnails/$thumbnailFileName' target='_blank' class='thumbnail-link'><img class='thumbnail' src='$thumbnailFileName' alt='Thumbnail'></a>
            </div>
            <div class='right-column'>
                <div class='title'>$title</div>
                <div class='date'>$date</div>
                <div class='user'>$user</div>
                <div class='url'><a href='$url' target='_blank'>$url</a></div>
                <div class='description'>$description</div>
            </div>
        </div>
    ";
    } else {

    $htmlContainer = "
        <div class='container2'>                
                <div class='title2'>$title</div>
                <div class='description2'>$description</div>
                <div class='date2'>$date</div>
                <div class='user2'>$user</div>
                <div class='url2'><a href='$url' target='_blank'>$url</a></div>                
                <a href='$thumbnailFileName' target='_blank' class='thumbnail-link2'>View thumbnail</a>
        </div>";

    }

  while(true){

    $table_count = "_" . $x;

    $next_table_count = "_" . ($x + 1);

    if ($x == 1){$table_count = "";}

    $current_table = $targetDirectory . $table . $table_count . ".html";

    $next_table = $table . $next_table_count . ".html";

    $file_size = filesize("$current_table");

    if($file_size < $max_table_size){

    if(!file_exists($current_table)){
      $cssFile = "<link rel='stylesheet' type='text/css' href='../containers2.css'><div class='next-page'><a href='$next_table'>next</a></a></div>";
    } else {
      $cssFile = "";
    }

    $htmlContainer = $cssFile . $htmlContainer; 

    $fp = fopen($current_table, "a");     
    fwrite($fp, $htmlContainer);
    fclose($fp); 

    echo "Content saved successfully! View <a href='$current_table' target='_blank'>$current_table</a>";

      break;
    }
  $x++;
  }
  
} else {
    //echo "Invalid request.";
}
?>
