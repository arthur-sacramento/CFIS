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
    ('User1', 'hash1', '2024-01-11', 1),
    ('User2', 'hash1', '2024-02-21', 5),
    ('User1', 'hash2', '2024-03-13', 40),
    ('User2', 'hash2', '2024-04-08', 5),
    ('User3', 'hash3', '2024-05-09', 5),
    ('User4', 'hash4', '2024-05-01', 2)
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
//$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Hash Growth Chart</title>
    <!-- Include Chart.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div style="width: 80%; margin: auto;">
        <canvas id="growthChart"></canvas>
    </div>

    <script>
        // Fetch PHP data and format it for Chart.js
        <?php
        $result = $conn->query($query);
        $chartData = [];

        while ($row = $result->fetch_assoc()) {
            $chartData[] = [
                'filehash' => $row['filehash'],
                'total_value' => $row['total_value'],
                'percentage' => $row['percentage'],
                'dates' => [] // Add an array to store dates
            ];

            // Fetch dates and values for each file hash
            $datesValuesQuery = "SELECT date, value FROM your_table_name WHERE filehash = '{$row['filehash']}' ORDER BY date";
            $datesValuesResult = $conn->query($datesValuesQuery);

            $runningTotal = 0;
            while ($dateValueRow = $datesValuesResult->fetch_assoc()) {
                $runningTotal += $dateValueRow['value'];
                $chartData[count($chartData) - 1]['dates'][] = [
                    'date' => $dateValueRow['date'],
                    'value' => $dateValueRow['value'],
                    'running_total' => $runningTotal,
                ];
            }
        }
        ?>

        // Convert PHP data to JavaScript
        var chartData = <?php echo json_encode($chartData); ?>;

        // Create a line chart using Chart.js
        var ctx = document.getElementById('growthChart').getContext('2d');
        var growthChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData[0].dates.map(item => item.date), // Assuming all file hashes have the same dates
                datasets: chartData.map(item => ({
                    label: 'Total Value Growth - ' + item.filehash,
                    data: item.dates.map(d => d.running_total),
                    borderColor: getRandomColor(),
                    borderWidth: 2,
                    fill: false
                }))
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Function to generate random color
        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
    </script>
</body>
</html>