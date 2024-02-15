<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="icons/logo.jpg">
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
<body>
    <?php
        // Assume $conn is the database connection
        session_start();

        // Database connection details
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "cfis";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Retrieve user data based on session variable
        $user = $_SESSION['user'];
        $sql = "SELECT * FROM users WHERE username = $user";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $userData = $result->fetch_assoc();
        } else {
            // Handle error or redirect to login page
        }
    ?>

    <div class="update-form">
        <h2>Update User Information</h2>
        <form id="updateUserForm" action="update_user.php" method="post">

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?php echo $userData['password']; ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $userData['mail']; ?>" required>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?php echo $userData['phone']; ?>">
           
            <label for="btc">BTC:</label>
            <input type="text" id="btc" name="btc" value="<?php echo $userData['btc']; ?>">

            <label for="pix">PIX:</label>
            <input type="text" id="pix" name="pix" value="<?php echo $userData['pix']; ?>">

            <label for="paypal">PayPal:</label>
            <input type="text" id="paypal" name="paypal" value="<?php echo $userData['paypal']; ?>">

            <button type="button" onclick="showConfirmationModal()">Update Information</button>
        </form>
    </div>

    <!-- Modal -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <p>Are you sure you want to update your information?</p>
            <button class="modal-button" onclick="proceedUpdate()">Yes</button>
            <button class="modal-button" onclick="hideModal()">No</button>
        </div>
    </div>

    <script>
        function showConfirmationModal() {
            var modal = document.getElementById('confirmationModal');
            modal.style.display = 'flex';
        }

        function hideModal() {
            var modal = document.getElementById('confirmationModal');
            modal.style.display = 'none';
        }

        function proceedUpdate() {
            // You can add code here to submit the form or redirect to the update script
            document.getElementById('updateUserForm').submit();
        }
    </script>
</body>
</html>
