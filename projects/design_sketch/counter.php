<?php

$counterFilePath = 'img/counter.txt';
$filesDirectory = 'img';

// Check if counter.txt exists and has a value
if (file_exists($counterFilePath)) {
    $counter = intval(file_get_contents($counterFilePath));
    $counter++;
} else {
    // If counter.txt doesn't exist or doesn't have a value, list files in 'files' directory
    $fileList = scandir($filesDirectory);
    
    // Exclude '.' and '..' from the list
    $fileList = array_diff($fileList, array('.', '..'));

    // Count the number of files in the 'files' directory
    $counter = count($fileList);
}

// Update counter.txt with the new value
file_put_contents($counterFilePath, $counter);


?>
