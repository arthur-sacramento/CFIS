<?php

include("db_connect.php");

// SQL query to find the most repeated 'url' in descending order
$sql = "SELECT url, COUNT(url) AS url_count
        FROM file_information
        GROUP BY url
        ORDER BY url_count DESC";

$result = $conn->query($sql);

// Display the results
if ($result->num_rows > 0) {
    echo "<h2>Most Repeated URLs</h2>";
    echo "<table border='1'>";
    echo "<tr><th>URL</th><th>Count</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['url']}</td>";
        echo "<td>{$row['url_count']}</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No results found.";
}

// Close the database connection
$conn->close();

?>