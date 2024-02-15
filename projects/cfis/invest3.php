<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investment Form</title>

    <style>
        /* Styles for the modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Investment Form</h2>
    <form id="investmentForm" action="process_investment.php" method="post">
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
            <button id="confirmModalButton">Yes</button>
            <button id="cancelModalButton">No</button>
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
            modal.style.display = "block";
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