<html>
<body> 
<head>
<title>Simple Chat</title>
<style>

   body {
       font-family: verdana;
   }

   input {
       padding: 10px;
   }

</style>
</head>
<form method='post' action=''>
<input type='text' name='name' placeholder='Username'>
<input type='text' name='message' placeholder='Message'>
<input type='text' name='category' placeholder='Category'>
<input type='submit' name='submit' value='Submit'>
</form>

<hr>
<br>

<?php

$folder = 'comments';
$date = date('Y-m-d');

// Use the hash of the current date (for home page) or provided hash from GET parameter as filename to save the contents

$fileHash = isset($_GET['hash']) ? sha1($_GET['hash']) : sha1($date);

$fileHash = isset($_POST['category']) ? sha1($_POST['category']) : $fileHash;

$filePath = "$folder/$fileHash";

if (isset($_POST['submit'])) {  

  $categoryHash = isset($_GET['category']) ? sha1($_GET['category']) : sha1($date);

  // Input manipulation
  $name = htmlspecialchars($_POST['name']);
  $message = htmlspecialchars($_POST['message']);
  $messageHash = sha1("$name $message");
  $messageWrite = "<a href='comment.php?hash=$messageHash'>[comment]</a> $date <b>$name</b> : $message <hr>";

  // Write the file
  $file = fopen($filePath, 'a');
  fwrite($file, $messageWrite);
  fclose($file);

}

// Show the updated page
if (file_exists($filePath)) {
    echo $messages = file_get_contents($filePath);
} else {
    echo "<u>No comments yet.</u>";
}

?>
