<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Art sketcher</title>
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
</style>
</head>

<?php
// Create a new image
$width = 800;
$height = 600;
$outputImage = imagecreatetruecolor($width, $height);

// Generate a random background color
$bgColor = imagecolorallocate($outputImage, rand(0, 255), rand(0, 255), rand(0, 255));
imagefill($outputImage, 0, 0, $bgColor);

// Generate random lines
$lineColor = imagecolorallocate($outputImage, rand(0, 255), rand(0, 255), rand(0, 255));
$numLines = rand(5, 15);

// Calculate the starting point of the first line
$startX = rand(0, $width);
$startY = rand(0, $height);

for ($i = 0; $i < $numLines; $i++) {
    // Calculate the ending point of the current line
    $endX = rand(0, $width);
    $endY = rand(0, $height);

    // Draw the line
    imageline($outputImage, $startX, $startY, $endX, $endY, $lineColor);

    // Update the starting point for the next line
    $startX = $endX;
    $startY = $endY;
}

// Save the generated image
$outputDirectory = 'img/';

if (!is_dir($outputDirectory)) {
    mkdir($outputDirectory, 0777, true);
}

include("counter.php");

$outputFilename = $outputDirectory . $counter .'.jpg';
imagejpeg($outputImage, $outputFilename);

// Clean up
imagedestroy($outputImage);

$lastPicture = "<a href='comment.php?hash=$outputFilename'><img src='$outputFilename' width='100%'></a>";
//echo "Image saved in: <a href='$outputFilename'>$outputFilename</a><br><img src='$outputFilename'>";
?>

<div id='view'></div>
<script>
    // This script createof a simple gallery picking random images.
    var maxw = <?php echo $counter ?>;

    var text = "Generator of simple and minimalist design sketches<br>by <b>Arthur S. Sacramento</b><br><br><a href='../../index.html'>Home</a><br>All rights reserved";

    function getRandomNumber() {
        return Math.floor(Math.random() * maxw);
    }

    function generateImageLink(category, random) {
        return `<a href='comment.php?hash=${category}/${random}.jpg' target='_blank'><img src='${category}/${random}.jpg' width='50%'></a>`;
    }

    var random1 = getRandomNumber();
    var random2 = getRandomNumber();
    var random3 = getRandomNumber();
    var random4 = getRandomNumber();
    var random5 = getRandomNumber();
    var random6 = getRandomNumber();
    var random7 = getRandomNumber();

    var img1 = generateImageLink("img", random1);
    var img2 = generateImageLink("img", random2);
    var img3 = generateImageLink("img", random3);
    var img4 = generateImageLink("img", random4);
    var img5 = generateImageLink("img", random5);
    var img6 = generateImageLink("img", random6);
    var img7 = generateImageLink("img", random7);

    var tableHtml = `<table width='100%'><tr><td valign='top' width='50%'><?php echo $lastPicture ?>${text}</td><td valign='top' width='50%'>${img2}${img3}${img4}${img5}${img6}${img7}</td></tr></table>`;

    document.getElementById("view").innerHTML = tableHtml;
</script>