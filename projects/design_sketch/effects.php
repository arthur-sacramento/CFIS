<!DOCTYPE html>
<html>
<head>
    <title>Image Filters and Effects</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        h2 {
            color: #333;
        }
        form {
            margin-top: 20px;
        }
        input[type="file"] {
            margin-bottom: 10px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 8px 16px;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        img {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
        }
        .image-container {
            display: flex;
            flex-wrap: wrap;
        }
        .image-preview {
            width: calc(33.33% - 20px);
            margin: 10px;
            border: 1px solid #ddd;
            padding: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
    </style>
</head>
<body>

<h2>Apply Filters/Effects</h2>

<?php
$directory = "img";
$allowedFormats = ["jpg", "jpeg", "png"];

// Check if the directory exists
if (is_dir($directory)) {
    // Open the directory
    if ($dh = opendir($directory)) {
        // Loop through each file in the directory
        while (($file = readdir($dh)) !== false) {
            // Check if the file is a valid image file
            $imageFileType = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($imageFileType, $allowedFormats)) {
                // Load the image
                $uploadedFile = $directory . "/" . $file;
                $image = imagecreatefromstring(file_get_contents($uploadedFile));

                // Apply a dozen different filter/effects

                // Grayscale
                imagefilter($image, IMG_FILTER_GRAYSCALE);
                imagepng($image, $directory . "/gray_" . $file);

                // Sepia
                imagefilter($image, IMG_FILTER_COLORIZE, 90, 60, 30);
                imagepng($image, $directory . "/sepia_" . $file);

                // Invert Colors
                imagefilter($image, IMG_FILTER_NEGATE);
                imagepng($image, $directory . "/invert_" . $file);

                // Brightness Adjustment
                imagefilter($image, IMG_FILTER_BRIGHTNESS, -50);
                imagepng($image, $directory . "/brightness_" . $file);

                // Blur
                imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR);
                imagepng($image, $directory . "/blur_" . $file);

                // Pixelate
                imagefilter($image, IMG_FILTER_PIXELATE, 10, true);
                imagepng($image, $directory . "/pixelate_" . $file);

                // Edge Detection
                imagefilter($image, IMG_FILTER_EDGEDETECT);
                imagepng($image, $directory . "/edge_" . $file);

                // Emboss
                imagefilter($image, IMG_FILTER_EMBOSS);
                imagepng($image, $directory . "/emboss_" . $file);

                // Mean Removal
                imagefilter($image, IMG_FILTER_MEAN_REMOVAL);
                imagepng($image, $directory . "/mean_" . $file);

                // Selective Color
                imagefilter($image, IMG_FILTER_CONTRAST, -50);
                imagefilter($image, IMG_FILTER_COLORIZE, 255, 0, 0);
                imagepng($image, $directory . "/color_" . $file);

                // Contrast
                imagefilter($image, IMG_FILTER_CONTRAST, -30);
                imagepng($image, $directory . "/contrast_" . $file);

                // Smooth
                imagefilter($image, IMG_FILTER_SMOOTH, 5);
                imagepng($image, $directory . "/smooth_" . $file);

                // Display the original and modified images
                echo '<p>Original Image:</p>';
                echo '<img src="' . $uploadedFile . '" alt="Original Image"><br><br>';
                echo '<p>Modified Image:</p>';
                echo '<img src="' . $directory . "/smooth_" . $file . '" alt="Modified Image"><br><br>';
            }
        }
        // Close the directory
        closedir($dh);
    }
}
?>
<form method="post">
    <input type="submit" value="Apply" name="submit">
</form>

</body>
</html>
