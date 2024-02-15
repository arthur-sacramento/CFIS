<?php

include("db_connect.php");

// SQL query to count occurrences of each hash
$sql = "SELECT sha256_hash, COUNT(*) AS hash_count FROM file_information GROUP BY sha256_hash";

// Execute the query
$result = $conn->query($sql);

// Check if there are any results
if ($result->num_rows > 0) {

    // Array to store data for the graph
    $data = array();

    // Fetch data and store it in the array
    while ($row = $result->fetch_assoc()) {
        $data[$row['sha256_hash']] = $row['hash_count'];
    }

    // Close the connection
    $conn->close();

    // Create a simple bar graph using the data
    // (You can use a graphing library like Chart.js or Google Charts for a more sophisticated graph)
    echo '<html>
            <head>
                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                <script type="text/javascript">
                    google.charts.load("current", {packages:["corechart"]});
                    google.charts.setOnLoadCallback(drawChart);
                    function drawChart() {
                        var data = new google.visualization.DataTable();
                        data.addColumn("string", "Hash");
                        data.addColumn("number", "Count");

                        data.addRows([';

    foreach ($data as $hash => $count) {
        echo '["' . $hash . '", ' . $count . '],';
    }

    echo ']);';

    echo 'var options = {
                    title: "Hash Occurrences Over Time",
                    width: 800,
                    height: 400
                };

                var chart = new google.visualization.BarChart(document.getElementById("chart_div"));
                chart.draw(data, options);
            }
        </script>
    </head>
    <body>
        <div id="chart_div"></div>
    </body>
    </html>';

} else {
    echo "No data available";
}

?>