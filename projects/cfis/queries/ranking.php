<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top 10 Filehash Values</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
        }

        h2 {
            color: #333;
        }

        .result-container {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
        }

        .filehash-info {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .last-five-values {
            margin-top: 10px;
        }

        .variance {
            margin-top: 10px;
            color: #007bff;
        }

        .error {
            color: #ff0000;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php

include("db_connect.php");

/// Select the top ten 'filehash' values with the most sum of 'value'
$sqlTop10 = "
    SELECT filehash, SUM(value) AS total_value
    FROM your_table_name
    GROUP BY filehash
    ORDER BY total_value DESC
    LIMIT 10
";

$resultTop10 = $conn->query($sqlTop10);

if ($resultTop10->num_rows > 0) {
    echo "<h2>Top 10 'filehash' with the most sum of 'value':</h2>";

    while ($rowTop10 = $resultTop10->fetch_assoc()) {
        $filehash = $rowTop10['filehash'];
        $totalValue = $rowTop10['total_value'];

        echo "<div class='result-container'>";
        echo "<div class='filehash-info'>Filehash: " . $filehash . ", Total Value: " . $totalValue . "</div>";

        // Select the last five entries for the current 'filehash'
        $sqlLastFive = "
            SELECT date, value
            FROM your_table_name
            WHERE filehash = '$filehash'
            ORDER BY date DESC
            LIMIT 5
        ";

        $resultLastFive = $conn->query($sqlLastFive);

        if ($resultLastFive->num_rows > 0) {
            echo "<div class='last-five-values'>";
            echo "<strong>Last Five Entries:</strong><br>";

            while ($rowLastFive = $resultLastFive->fetch_assoc()) {
                echo "Date: " . $rowLastFive['date'] . ", Value: " . $rowLastFive['value'] . "<br>";
            }

            echo "</div>";
        } else {
            echo "<div class='error'>Not enough data for the last five entries.</div>";
        }

        echo "</div>"; // Closing result-container div
    }
} else {
    echo "<div class='error'>0 results</div>";
}
?>

</body>
</html>