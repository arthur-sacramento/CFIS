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

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $selectedInterval = $_POST["time_interval"];

        // Determine the appropriate date condition based on the selected interval
        switch ($selectedInterval) {
            case "day":
                $dateCondition = "AND date = CURDATE()";
                break;
            case "week":
                $dateCondition = "AND date >= CURDATE() - INTERVAL 1 WEEK";
                break;
            case "month":
                $dateCondition = "AND date >= CURDATE() - INTERVAL 1 MONTH";
                break;
            case "year":
                $dateCondition = "AND date >= CURDATE() - INTERVAL 1 YEAR";
                break;
            default:
                $dateCondition = "";
        }

        // Query to calculate the sum of 'value' for each 'filehash' based on the selected interval
        $sumByIntervalQuery = "SELECT
            filehash,
            date,
            SUM(value) AS total_value
        FROM
            your_table_name
        WHERE 1 $dateCondition
        GROUP BY
            filehash, date
        ORDER BY
            filehash, date";

        $result = $conn->query($sumByIntervalQuery);

        if ($result->num_rows > 0) {
            echo "<h2>Sum of 'value' for each 'filehash' based on the selected interval:</h2>";
            echo "<table border='1'>";
            echo "<tr><th>Filehash</th><th>Date</th><th>Total Value</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>{$row['filehash']}</td><td>{$row['date']}</td><td>{$row['total_value']}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "No results found";
        }
    }
    ?>

    <!-- HTML form for selecting the time interval -->
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="time_interval">Select Time Interval:</label>
        <select name="time_interval" id="time_interval">
            <option value="day">Last Day</option>
            <option value="week">Last Week</option>
            <option value="month">Last Month</option>
            <option value="year">Last Year</option>
        </select>
        <input type="submit" value="Submit">
    </form>

    <?php
    // Close connection
    $conn->close();
    ?>
</body>
</html>