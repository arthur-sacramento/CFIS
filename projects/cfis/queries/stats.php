<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .update-form {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .modal-button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 10px;
        }
    </style>
</head>

<?php

include("db_connect.php");

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input
    $userInputFilehash = $_POST['filehash'];

    // Fetch data for the specified 'filehash'
    $sql = "SELECT user, filehash, date, value FROM your_table_name WHERE filehash = '$userInputFilehash' ORDER BY date";
    $result = $conn->query($sql);

    $data = []; // Array to store data for the chart

    if ($result->num_rows > 0) {
        $previousValue = null;

        while ($row = $result->fetch_assoc()) {
            $currentValue = $row['value'];

            // Calculate percentage change
            $percentageChange = null;
            if ($previousValue !== null) {
                $percentageChange = (($currentValue - $previousValue) / $previousValue) * 100;
            }

            // Add data to the array
            $data[] = [
                'user' => $row['user'],
                'filehash' => $row['filehash'],
                'date' => $row['date'],
                'value' => $row['value'],
                'percentageChange' => $percentageChange,
            ];

            // Update previousValue for the next iteration
            $previousValue = $currentValue;
        }
    } else {
        echo "0 results";
    }

    // Close the connection
    $conn->close();

    // Convert PHP data to JSON for JavaScript
    $dataJson = json_encode($data);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Percentage Change Chart</title>
    <!-- Include Google Charts library -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>

<!-- Form for user input -->
<form method="post" action="">
    <label for="filehash">Enter Filehash:</label>
    <input type="text" name="filehash" id="filehash" required>
    <button type="submit">Generate Chart</button>
</form>

<!-- Google Charts container -->
<div id="chart_div" style="width: 400px; height: 200px;"></div>

<script>
// JavaScript code for Google Charts

// Check if data is available before generating the chart
if (typeof <?php echo isset($dataJson) ? 'true' : 'false'; ?>) {
    // Parse the PHP data from JSON to JavaScript
    var parsedData = <?php echo isset($dataJson) ? $dataJson : '[]'; ?>;
    console.log(parsedData); // Check the data structure in the browser console

    // Load the Google Charts library
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    // Function to draw the line chart
    function drawChart() {
        // Create a DataTable from the data
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Date');
        data.addColumn('number', 'Percentage Change');

        // Add rows to the DataTable
        for (var i = 0; i < parsedData.length; i++) {
            data.addRow([parsedData[i].date, parsedData[i].percentageChange]);
        }

        // Set chart options
        var options = {
            title: 'Percentage Change Chart',
            curveType: 'function',
            legend: { position: 'bottom' },
            vAxis: {
                title: 'Percentage Change',
                format: '#%'
            }
        };

        // Create and draw the line chart
        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
}
</script>

</body>
</html>