<?php

include("db_connect.php");

// Select data from the table and calculate the sum of 'value' for each 'filehash'
$sql = "SELECT filehash, SUM(value) AS total_value FROM your_table_name GROUP BY filehash ORDER BY total_value DESC LIMIT 10";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "Top 10 'filehash' with the most sum of 'value':<br>";

    while ($row = $result->fetch_assoc()) {
        echo "Filehash: " . $row['filehash'] . ", Total Value: " . $row['total_value'] . "<br>";
    }
} else {
    echo "0 results";
}

// Close the connection
$conn->close();
?>