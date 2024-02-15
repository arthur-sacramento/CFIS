<?php

include("db_connect.php");

// Create the table
$tableCreationQuery = "CREATE TABLE IF NOT EXISTS invest (
    user VARCHAR(255),
    filehash VARCHAR(255),
    date DATE,
    value INT
    -- Add any other necessary columns and constraints here
)";

if ($conn->query($tableCreationQuery) === TRUE) {
    echo "Table created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Insert sample data
$sampleDataQuery = "INSERT INTO invest (user, filehash, date, value)
VALUES
    ('User1', 'hash1', '2024-01-01', 10),
    ('User2', 'hash1', '2024-01-02', 15),
    ('User1', 'hash2', '2024-01-03', 20),
    ('User2', 'hash2', '2024-01-04', 25),
    ('User3', 'hash3', '2024-01-04', 5)
    -- Add more sample data as needed
";

if ($conn->query($sampleDataQuery) === TRUE) {
    echo "Sample data inserted successfully<br>";
} else {
    echo "Error inserting sample data: " . $conn->error . "<br>";
}

// Query to sum 'value' of each 'filehash' in descending order
// Query to sum 'value' of each 'filehash' in descending order
$query = "SELECT filehash, SUM(value) AS total_value
FROM invest
GROUP BY filehash
ORDER BY total_value DESC";

$result = $conn->query($query);

if ($result === FALSE) {
    die("Error executing query: " . $conn->error);
}

if ($result->num_rows > 0) {
    echo "<h2>Results:</h2>";
    while ($row = $result->fetch_assoc()) {
        echo "Filehash: " . $row["filehash"] . " - Total Value: " . $row["total_value"] . "<br>";
    }
} else {
    echo "No results found";
}


// Close connection
$conn->close();
?>
