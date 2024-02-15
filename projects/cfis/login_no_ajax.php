<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CFIS</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: space-between;
        }

        .resume {
            padding: 20px 0px 0px 20px; /* Adjust the width as needed */
        }

        .full-container {
            width: 100%; /* Adjust the width as needed */
        }

        .left-container {
            width: 100%; /* Adjust the width as needed */
        }

        .right-container {
            width: 1%; /* Adjust the width as needed */         
        }

        .simple-container {
            margin: 2% 2%;
            padding: 20px;    
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }


        .left-container input[type="text"] {
            width: 66%;
            display: inline-block;
            margin-right: 2%;
        }

        .left-container input[type="submit"] {
             width: 25%;
             display: inline-block;
             margin-right: 2%;
        }

        .centerForm {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
        }

        input {
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }

        img {
            padding-top: 5px; 
        }

        .reservedRights {
            padding-left: 2%; 
            color: #AAA;
            letter-spacing: 3px;
        }

        .reservedRights a{
            padding-left: 1%; 
            color: #AAA;
            letter-spacing: 3px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .reservedRights a:hover {
            color: #000; /* Change the color to black on hover */
        } 

 .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 900;
        }

        /* Styling for the "Open menu" link */
        .open-menu-link {
            cursor: pointer;
            color: #007bff;
            text-decoration: underline;
        }

.modal button {
    background-color: #AAA;
    color: #fff;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.modal button:hover {
    background-color: #999;
}

    </style>
</head>
<body>

<div class="overlay" id="overlay" onclick="closeModal()"></div>
<div class="modal" id="modal">
    <p>Project by Arthur S. Sacramento</p>
    <p>All rigths reserved</p> 
    <button onclick="closeModal()">Close</button>
</div>


<div class="left-container">

<div class='resume'>
     <h1>Centralized File Information Service</h1>
    Our objective is checks if a file exists at the provided URL and retrieves some information.<br>
    This tool is useful for indirectly assessing the popularity or disponibility of a file based on the number of sites that host it.

</div>
   

<?php

echo $userOn = isset($_SESSION['user']) ? $_SESSION['user'] : "";

//echo Welcome $_SESSION['user'];

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CFIS";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    mail VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    files VARCHAR(255),
    credits VARCHAR(20),
    ranking VARCHAR(20),
    likes INT(6),
    dislikes INT(6),
    reports INT(6),
    btc VARCHAR(50),
    pix VARCHAR(50),
    paypal VARCHAR(255)
)";

if ($conn->query($sql) === TRUE) {
    //echo "Table created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Create files table
$sql = "CREATE TABLE IF NOT EXISTS file_information (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(255) NOT NULL,
    file_size VARCHAR(20) NOT NULL,
    date DATETIME NOT NULL,
    sha256_hash VARCHAR(64) NOT NULL,
    user VARCHAR(50),
    filename VARCHAR(255),
    description VARCHAR(255),
    connection_delay FLOAT(10,4) NOT NULL,
    mime_type VARCHAR(255)
)";

if ($conn->query($sql) === TRUE) {
    //echo "Table created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Handle user registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $mail = $_POST["mail"];
    $phone = $_POST["phone"];
    $btc = $_POST["btc"];
    $pix = $_POST["pix"];
    $paypal = $_POST["paypal"];

    $insertSql = "INSERT INTO users (username, password, mail, phone, btc, pix, paypal) 
                  VALUES ('$username', '$password', '$mail', '$phone', '$btc', '$pix', '$paypal')";

    if ($conn->query($insertSql) === TRUE) {
        echo "User registered successfully<br>";
    } else {
        echo "Error registering user: " . $conn->error . "<br>";
    }
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $username = $_POST["login_username"];
    $password = $_POST["login_password"];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row["password"];

        if (password_verify($password, $hashedPassword)) {
            echo "Login successful!<br>";
            $_SESSION['user'] = $username;
        } else {
            echo "Invalid password<br>";
        }
    } else {
        echo "User not found<br>";
    }
}

// Handle file link
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["file"])) {

// Get values from the form
$filename = $_POST['filename'];
$filehash = $_POST['filehash'];
$url = $_POST['url'];
$description = $_POST['description'];

// SQL query to insert data into the 'files' table
$sql = "INSERT INTO files (filename, filehash, url, description) VALUES ('$filename', '$filehash', '$url', '$description')";

// Execute query
if ($conn->query($sql) === TRUE) {
    echo "File information inserted successfully";
} else {
    echo "Error inserting file information: " . $conn->error;
}
}

// Handle the search
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"]) || isset($_GET["page"])) {

echo "<br>";

$userInput = $_POST['search']; // Assuming user input is obtained via POST

// Sanitize the user input (you should use a more robust method based on your specific requirements)
// Sanitize the user input (you should use a more robust method based on your specific requirements)
$userInput = $conn->real_escape_string($userInput);

$resultsPerPage = 10;

// Get the current page number from the URL
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $resultsPerPage;

// SQL query with pagination
$sql = "SELECT * FROM file_information 
        WHERE url LIKE '%$userInput%' 
           OR sha256_hash LIKE '%$userInput%' 
           OR filename LIKE '%$userInput%' 
           OR description LIKE '%$userInput%'
        LIMIT $offset, $resultsPerPage";

// Execute the query
$result = $conn->query($sql);

// Check for errors in the query
if ($result === false) {
    die("Error in query: " . $conn->error);
}

// Check if there are results
if ($result->num_rows > 0) {
    // Output data of each row
    echo "<table border='0'>";
    echo "<tr>
            <th>ID</th>
            <th>URL</th>
            <th>Size</th>
            <th>Date</th>
            <th>User</th>
            <th>Filename</th>
            <th>Description</th>
            <th>Delay</th>
          </tr>";

    $rowNumber = 0; // Initialize row number counter

    while ($row = $result->fetch_assoc()) {
        // Determine background color based on row number
        $backgroundColor = $rowNumber % 2 == 0 ? 'background-color: #DDD;' : 'background-color: white;';

        // Output row with the specified background color
        echo "<tr style='{$backgroundColor}'>
                <td>{$row['id']}</td>
                <td><a href='{$row['url']}' target='_blabk'>{$row['url']}</a></td>
                <td>{$row['file_size']}</td>
                <td>{$row['date']}</td>                
                <td>{$row['user']}</td>
                <td>{$row['filename']}</td>
                <td>{$row['description']}</td>
                <td>{$row['connection_delay']}</td>
              </tr>";

        $rowNumber++; // Increment row number counter
    }

    echo "</table>";

    // Pagination links
    $totalPages = ceil($result->num_rows / $resultsPerPage);
    echo "<div>";
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a href='?page=$i'>$i</a> ";
    }
    echo "</div>";

} else {
    echo "0 results";
}


}



$conn->close();
?>


  <div class="simple-container">
         <form action="" method="post">
            <!-- Login form fields here -->
             <label for="username">URL:</label><br>
            <input type="text" name="username" placeholde="URL"><input type="submit" name="register" value="Check">
        </form>
     </div>  

    <div class="simple-container">
        <form action="" method="post">
            <!-- Registration form fields here -->
<h2>Registration</h2>
<table width="100%"><tr><td>
           
           <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>

            <input type="email" name="mail" placeholder="E-mail" required><br>

            <input type="text" name="phone" placeholder="Phone">
</td><td>
  
            <input type="text" name="btc" placeholder="Bitcoin wallet"><br>

            
            <input type="text" name="pix" placeholder="Pix"><br>


            <input type="text" name="paypal" placeholder="PayPal"><br>

            <input type="submit" name="register" value="Register">
        </form>

</td></tr></table>
    </div>
    

<span class='reservedRights'>
2023- all rights reserved
</span>

</div>

 <div class="right-containers">

  <div class="simple-container">
        <h2>Login</h2>
        <form action="" method="post" class="centerForm">
            <!-- Login form fields here -->
            <label for="login_username">Username:</label>
            <input type="text" name="login_username" required>
            <label for="login_password">Password:</label>
            <input type="password" name="login_password" required><br>

            <input type="submit" name="login" value="Login">
        </form>
     </div>   

  <div class="simple-container">
        <h2>Search</h2>
        <form action="" method="post" class="centerForm">
            <!-- Login form fields here -->
            <input type="text" name="search" placeholder="search..." required>
            <input type="submit" name="searchButton" value="Search">
        </form>
     </div>  

<br>
<span class='reservedRights'>
<div align='center'>
<a href="#" class="open-menu-link" onclick="openModal()">Open menu</a>
</div>
</span>
<br>

 </div>
<script>
    // JavaScript functions to open and close the modal
    function openModal() {
        document.getElementById("overlay").style.display = "block";
        document.getElementById("modal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("overlay").style.display = "none";
        document.getElementById("modal").style.display = "none";
    }
</script>

</body>
</html>