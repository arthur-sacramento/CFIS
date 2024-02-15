<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Hash Growth Analysis</title>
</head>
<body>

<?php

include("db_connect.php");

    // Create the table
    $createTableQuery = "CREATE TABLE IF NOT EXISTS your_table_name (
        user VARCHAR(255),
        filehash VARCHAR(255),
        date DATE,
        value INT
    )";
    
    if ($conn->query($createTableQuery) === TRUE) {
        echo "Table created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }

    // Insert sample data
    $insertDataQuery = "INSERT INTO your_table_name (user, filehash, date, value)
    VALUES
        ('User1', 'hash1', '2024-03-01', 100),
        ('User2', 'hash1', '2024-03-01', 25),
        ('User1', 'hash2', '2024-03-01', 2),
        ('User2', 'hash2', '2024-03-01', 1)";
    
    if ($conn->query($insertDataQuery) === TRUE) {
        echo "Sample data inserted successfully<br>";
    } else {
        echo "Error inserting sample data: " . $conn->error . "<br>";
    }

    // Query to calculate the sum of 'value' for each 'filehash' and organize by month
    $sumByMonthQuery = "SELECT
        filehash,
        MONTH(date) AS month,
        SUM(value) AS total_value
    FROM
        your_table_name
    GROUP BY
        filehash, MONTH(date)
    ORDER BY
        filehash, month";

    $result = $conn->query($sumByMonthQuery);

    if ($result->num_rows > 0) {
        echo "<h2>Sum of 'value' for each 'filehash' organized by month:</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Filehash</th><th>Month</th><th>Total Value</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>{$row['filehash']}</td><td>{$row['month']}</td><td>{$row['total_value']}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No results found";
    }

    // Query to calculate the percentage growth or decrease for each 'filehash' based on the last month
    $percentageChangeQuery = "SELECT
        t1.filehash,
        t1.month,
        t1.total_value,
        CASE
            WHEN t2.total_value IS NULL THEN 0
            ELSE (t1.total_value - t2.total_value) / t2.total_value * 100
        END AS percentage_change
    FROM
        ($sumByMonthQuery) t1
    LEFT JOIN
        ($sumByMonthQuery) t2 ON t1.filehash = t2.filehash AND t1.month = t2.month + 1
    ORDER BY
        t1.filehash, t1.month";

    $result = $conn->query($percentageChangeQuery);

    if ($result->num_rows > 0) {
        echo "<h2>Percentage growth or decrease for each 'filehash' based on the last month:</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Filehash</th><th>Month</th><th>Total Value</th><th>Percentage Change</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>{$row['filehash']}</td><td>{$row['month']}</td><td>{$row['total_value']}</td><td>{$row['percentage_change']}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No results found";
    }

    // Query to calculate the total 'value' sum of each 'filehash'
    $totalSumQuery = "SELECT
        filehash,
        SUM(value) AS total_value
    FROM
        your_table_name
    GROUP BY
        filehash
    ORDER BY
        total_value DESC";

    $result = $conn->query($totalSumQuery);

    if ($result->num_rows > 0) {
        echo "<h2>Total 'value' sum of each 'filehash':</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Filehash</th><th>Total Value</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>{$row['filehash']}</td><td>{$row['total_value']}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No results found";
    }

    // Close connection
    $conn->close();
    ?>
</body>
</html>