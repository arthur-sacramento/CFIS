<?php 
  error_reporting(0);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upload and Extract Zip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

  </style>
</head>

<body>

<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // File upload directory
    $uploadDir = "categories/";

    // Get the uploaded file name
    $zipFileName = basename($_FILES["file"]["name"]);

    // Get the file extension
    $fileExt = strtolower(pathinfo($zipFileName, PATHINFO_EXTENSION));

    // Allowed file extensions
    $allowedExt = array("zip");

    $folderName = pathinfo($zipFileName, PATHINFO_FILENAME);

    if (file_exists("categories/$folderName")){
        echo "Error: Sorry, this gallery already exists"; die;                          
    }

    // Check if the uploaded file is a zip file
    if (in_array($fileExt, $allowedExt)) {
        $uploadFilePath = $uploadDir . $zipFileName;        

        // Upload the zip file
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $uploadFilePath)) {
            // Extract the zip file
            $zip = new ZipArchive;
            if ($zip->open($uploadFilePath) === TRUE) {
                // Get the folder name (without extension)
                

                // Create a folder if it doesn't exist
                $folderPath = $uploadDir . $folderName . '/';
                if (!file_exists($folderPath)) {
                    //mkdir($folderPath, 0755, true);
                    mkdir($folderPath); 
                }    

                // Extract images to the folder
                for ($i = 0; $i < $zip->numFiles; $i++) {  

                    $extractedFileName = $zip->getNameIndex($i);
                    $extractedFileExt = strtolower(pathinfo($extractedFileName, PATHINFO_EXTENSION));         

                    if (in_array($extractedFileExt, array(""))) {
                        $zip->close();
                        unlink($uploadFilePath); // Delete the uploaded zip file
                        echo "Error: You upload a file with folders inside. Try select and zip only the pictures"; die;  
                    }

                    if (in_array($extractedFileExt, array("php"))) {
                        $zip->close();
                        unlink($uploadFilePath); // Delete the uploaded zip file
                        echo "Error: You can't upload php files"; die;  
                    }

                    if (in_array($extractedFileExt, array("jpg", "jpeg", "png", "gif"))) {
                        //$extractedFilePath = $folderPath . $extractedFileName;
                        //$zip->extractTo($folderPath, array($extractedFileName));
                        $zip->extractTo("categories/$folderName" . '/', array($extractedFileName));
                        //$zip->extractTo($uploadDir);
                    }
                }
                $zip->close();
                echo "Zip file uploaded and extracted successfully.";
            } else {
                echo "Failed to extract zip file.";
            }
            unlink($uploadFilePath); // Delete the uploaded zip file
        } else {
            echo "Failed to upload zip file.";
        }
    } else {
        echo "Only zip files are allowed.";
    }

echo " Wait the thumbnail creation to view the gallery <a href='categories/$folderName/$folderName.html' target='_blank'>'categories/contents/$folderName.html'</a><br>";

/*/ Thumbs creations /*/

        $thumbFolder = $folderPath;
        $croppedFolder = $folderPath;
        $cropSize = 250;

        if (!is_dir($croppedFolder)) {
            mkdir($croppedFolder);
        }

        $images = scandir($thumbFolder);

        foreach ($images as $image) {
            if ($image !== '.' && $image !== '..') {
                $imagePath = $thumbFolder . '' . $image;
                $croppedPath = $croppedFolder . 'c_' . $image;

                // Get original image dimensions
                list($width, $height) = getimagesize($imagePath);

                // Calculate cropping position
                $cropX = max(0, ($width - $cropSize) / 2);
                $cropY = max(0, ($height - $cropSize) / 2);

                // Create a new image from the original
                $originalImage = imagecreatefromjpeg($imagePath);
                $croppedImage = imagecreatetruecolor($cropSize, $cropSize);

                // Crop and resize the image
                imagecopyresampled($croppedImage, $originalImage, 0, 0, $cropX, $cropY, $cropSize, $cropSize, $cropSize, $cropSize);

                // Save the cropped image
                imagejpeg($croppedImage, $croppedPath, 90);

                // Display the cropped image               
                echo '<img src="' . $croppedPath . '" alt="Cropped Image">';
          
                $gallery_item = $gallery_item . "<a href='$image' target='_blank'><img src='c_$image'></a>";

                // Free up memory
                imagedestroy($originalImage);
                imagedestroy($croppedImage);

            }
        }

  /*/ HTML page creation /*/

  $file_new = fopen("categories/$folderName/$folderName.html", "w");
  fwrite($file_new, "$gallery_item");
  fclose($file_new);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upload and Extract Zip</title>
</head>

<body>
    Upload a zip with pictures. (Zip only the pictures without any folder)

    <h2>Upload a Zip File</h2>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
        <input type="file" name="file" accept=".zip" required>
        <button type="submit">Upload</button>
    </form>
</body>

<hr>

</html>
