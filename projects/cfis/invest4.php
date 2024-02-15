<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investment Form</title>

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
    <form id="investmentForm" class="update-form" action="process_investment.php" method="post">
        <label for="value">Value:</label>
        <input type="text" name="value" required>
        
        <label for="filehash">File Hash:</label>
        <input type="text" name="filehash" required>

        <!-- Button to trigger the modal -->
        <button type="button" id="confirmButton">Submit</button>
    </form>

    <!-- Modal -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Are you sure you want to proceed with the investment?</p>
            <button id="confirmModalButton" class="modal-button">Yes</button>
            <button id="cancelModalButton" class="modal-button">No</button>
        </div>
    </div>

    <script>
        // Get the modal and buttons
        const modal = document.getElementById("confirmationModal");
        const confirmButton = document.getElementById("confirmButton");
        const confirmModalButton = document.getElementById("confirmModalButton");
        const cancelModalButton = document.getElementById("cancelModalButton");

        // Open the modal when the button is clicked
        confirmButton.addEventListener("click", () => {
            modal.style.display = "flex";
        });

        // Close the modal when the close button is clicked
        modal.querySelector(".close").addEventListener("click", () => {
            modal.style.display = "none";
        });

        // Close the modal when the cancel button is clicked
        cancelModalButton.addEventListener("click", () => {
            modal.style.display = "none";
        });

        // Proceed with the form submission when the confirm button in the modal is clicked
        confirmModalButton.addEventListener("click", () => {
            modal.style.display = "none";
            // Trigger the form submission
            document.getElementById("investmentForm").submit();
        });
    </script>
</body>
</html>
