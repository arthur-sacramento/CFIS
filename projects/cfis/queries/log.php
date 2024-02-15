<?php

include("db_connect.php");

// Select data from the table
$sql = "SELECT user, filehash, date, value FROM your_table_name ORDER BY filehash, date";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $previousValue = null;

    while ($row = $result->fetch_assoc()) {
        $currentValue = $row['value'];

        // Calculate percentage change
        $percentageChange = null;
        if ($previousValue !== null) {
            $percentageChange = (($currentValue - $previousValue) / $previousValue) * 100;
        }

        // Output or process the data as needed
        echo "User: " . $row['user'] . ", Filehash: " . $row['filehash'] . ", Date: " . $row['date'] . ", Value: " . $row['value'] . ", Percentage Change: " . $percentageChange . "%<br>";

        // Update previousValue for the next iteration
        $previousValue = $currentValue;
    }
} else {
    echo "0 results";
}

// Close the connection
$conn->close();
?>