<?php

include("db_connect.php");

// Create the table
$tableCreationQuery = "CREATE TABLE IF NOT EXISTS your_table_name (
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
$sampleDataQuery = "INSERT INTO your_table_name (user, filehash, date, value)
VALUES
    ('User1', 'hash1', '2024-01-01', 10),
    ('User2', 'hash1', '2024-02-02', 15),
    ('User1', 'hash2', '2024-03-03', 20),
    ('User2', 'hash2', '2024-04-04', 25),
    ('User3', 'hash3', '2024-05-04', 5)
    -- Add more sample data as needed
";

if ($conn->query($sampleDataQuery) === TRUE) {
    echo "Sample data inserted successfully<br>";
} else {
    echo "Error inserting sample data: " . $conn->error . "<br>";
}

// Query to sum 'value' of each 'filehash' and calculate percentage
$query = "SELECT filehash, SUM(value) AS total_value,
                 (SUM(value) / (SELECT SUM(value) FROM your_table_name)) * 100 AS percentage
          FROM your_table_name
          GROUP BY filehash
          ORDER BY total_value DESC";

$result = $conn->query($query);

if ($result === FALSE) {
    die("Error executing query: " . $conn->error);
}

if ($result->num_rows > 0) {
    echo "<h2>Results:</h2>";
    while ($row = $result->fetch_assoc()) {
        echo "Filehash: " . $row["filehash"] . " - Total Value: " . $row["total_value"] . " - Percentage: " . $row["percentage"] . "%<br>";
    }
} else {
    echo "No results found";
}

// Close connection
$conn->close();
?>
