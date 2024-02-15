<?php error_reporting(0); 

/*

  This script provides a simple way for the user share and search links without MySQL or any database. 
  HTML pages are created with the names of the provided categories and are appended with the links that users insert.

  ---------------------------------

  Creator : Arthur S. Sacramento
  Date: 2023-12-12

  PHP freelance contact

  http://wa.me/5591983608861
  arthur.sacramento@hotmail.com

*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" href="icons/logo.png">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simple link share</title>
  <style>        
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        a {
            color: #3366cc; /* Set the default text color */
            text-decoration: none; /* Remove underline */
        }

        a:hover {
            color: #ff9900; /* Set the text color on hover */
            text-decoration: underline; /* Add underline on hover */
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        .greenButton, .blueButton, .grayButtonLink {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .greenButton:hover {
            background-color: #45a049;
        }

        .blueButton {
            background-color: #3498db;
        }

        .blueButton:hover {
            background-color: #2980b9;
        }

        .grayButtonLink {
            background-color: #999;
        }

        .grayButtonLink:hover {
            background-color: #AAA;
            color: #FFF;
            text-decoration: none;
        }

        /* Logo style */
        .logo a{
            position: absolute;
            bottom: 20px;
            right: 20px;
            color: #999;
            font-size: 24px;
        }

        .logo a:hover{
            text-decoration: none;  
        }
    </style>

</head>
<body>
<div class="logo"><a href='simple_link_share1.0.zip' target='_blank'>SimpleLinkShare</a></div>

<?php

$about = $_GET['about'];

if($about){

    echo "©<b>Arthur Sacramento</b>. PHP Freelance : &nbsp; <a href='https://www.linkedin.com/in/arthur-sacramento-a55003230/' target='_blank'>Linkedin</a> &nbsp; <a href='http://wa.me/5591983608861' target='_blank'>Whatsapp</a> &nbsp; <a href='#' onclick=" . '"' . "alert('arthur.sacramento@hotmail.com')" . '"' . ">E-mail</a>";
    die;   
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $link = $_POST['link'];
    $category = $_POST['category'];
    $rootFolder = "categories";
    $filePath = "$rootFolder/$category/$category.html";

    $statusMsg = "&nbsp;Link inserted!";

    // Redirect to a searched category or display an error message. 
    if ($_POST['button'] == 2) {
        if (file_exists($filePath)) {
            header("Location: $filePath");
            exit;
        } else {
            $statusMsg = "&nbsp;File not found.";
        }
    }

    // Insert button
    if ($_POST['button'] == 1) {

        $date = date('Y-m-d');
        $filenameDate = date('Ymd');
        $maxFileSize = 10000;

        // Avoid insert script in HTML pages
        $avoidLinkInsert = ['<', '>'];
        // Avoid write in root directory path
        $avoidCategoryInsert = ['.', '..', '/'];   
                
        $link = str_replace($avoidLinkInsert, "", $link);       
        $category = str_replace($avoidCategoryInsert, "", $category);     

        $link = htmlspecialchars($link);   
        $category = htmlspecialchars($category);   

        if (empty($link)) {
           echo "Error : Empty link";     
           die;
        }

        $categories = array_map('trim', explode(',', $category));

        // The default CSS style and JavaScript path that each HTML page will load
        $header = "<html><head><link rel='stylesheet' type='text/css' href='../../default.css'><script src='../../header.js'></script></head><body><div id='headerjs'></div>";

        $categoryExceedsSize = "";

        // Identify the categories in user input
        foreach ($categories as $category) {
            $category = str_replace(" ", "_", $category);
            $pathFile = "$rootFolder/$category/$category.html";

            $fileSize = filesize($pathFile);

            // If the file exceeds the defined size, the filename will be the date.
            if($fileSize > $maxFileSize){
                $pathFile = "$rootFolder/$category/$filenameDate.html";
                $categoryExceedsSize = $pathFile;
            }  

            // If the file does not exist, it will be created with the path for CSS style and JavaScript element            
            if (file_exists($pathFile)) {                             
                $linkWrite = "$date <a href='$link' target='_blank'>$link</a><hr>";                 
            } else {
                $linkWrite = "$header $date <a href='$link' target='_blank'>$link</a><hr>";            
            }

            if (!is_dir("$rootFolder/$category")) {
                mkdir("$rootFolder/$category", 0755, true);
            }

            $file = fopen($pathFile, 'a');
            fwrite($file, $linkWrite);
            fclose($file);
        }

        if (empty($categoryExceedsSize)) {
            $firstCategory = reset($categories);
            $categoryLink = "$rootFolder/$firstCategory/$firstCategory.html";
        } else {
            $categoryLink = $pathFile;
        }
    
    $statusMsg = "&nbsp;<a href='$categoryLink' target='_blank'>Link inserted!</a>";

    }
}

?>

<form action="index.php" method="post">
  <label for="link">Link</label>
  <input type="text" id="link" name="link" placeholder="http://google.com">

  <label for="category">Category</label>
  <input type="text" id="category" name="category" placeholder="cats,dog,video" required>

  <button type="submit" name="button" value="1" class="greenButton">Insert</button>
  <button type="submit" name="button" value="2" class="blueButton">Search</button>
  <a href="index.php?about=true" class="grayButtonLink" onClick='alert("Project by Arthur S. Sacramento. All rights reserved.");'>About</a>

  <?php echo $statusMsg;?>   
</form>