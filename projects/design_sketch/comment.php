<html>
<body> 
<head>
<title>Simple Chat</title>
<style>

   body {
       font-family: verdana;
   }

   td {
       padding: 10px;
   }

   input {
       padding: 10px;
   }

</style>
</head>

<div align='center'>
<form method='post' action=''>
<input type='text' name='name' placeholder='Username'>
<input type='text' name='message' placeholder='Message'>
<input type='submit' name='submit' value='Submit'>
</form>

<hr>
<br>

<?php

if (!is_dir("comments")) {
  mkdir("comments");
}

$leftTableSize = file_exists($_GET['hash']) ? "50" : "0";
$leftTableSizePixel = $leftTableSize . "%";

$folder = 'comments';
$date = date('Y-m-d');

if(isset($_GET['hash'])){
  $getHash = $_GET['hash'];

  echo $table = "<table width='100%'><tr><td width='$leftTableSizePixel '>";
  $backHome = "<br><br><a href='index.php'>Back</a>";
  echo $img = $leftTableSize ? "<a href='$getHash'><img src='$getHash' width='100%'></a> $backHome" : "";
  echo $imgTableClose = "</td><td valign='top'>";
  //echo "<br>";
}

$fileHash = isset($_GET['hash']) ? sha1($_GET['hash']) : sha1($date);
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

echo "</td></tr></table>";
echo "</div>";
?>
