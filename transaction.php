<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Transaction Coordination Page">
    <title>Send Tokens</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
        }
        .container {
            margin-top: 50px;
            max-width: 600px;
        }
        .form-container {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            background-color: #ffffff;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Send Tokens</h1>
        <!-- Transaction Form -->
        <div class="form-container">
            <form id="transactionForm">
                <div class="mb-3">
                    <label for="recipientWallet" class="form-label">Recipient Wallet Address</label>
                    <input type="text" class="form-control" id="recipientWallet" required>
                </div>
                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" class="form-control" id="amount" min="0.1" step="0.1" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description (Optional)</label>
                    <textarea class="form-control" id="description" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Tokens</button>
                <div id="transactionMessage" class="alert mt-3 d-none"></div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle form submission
        document.getElementById('transactionForm').addEventListener('submit', async (event) => {
            event.preventDefault(); // Prevent default form submission

            // Get form values
            const recipientWallet = document.getElementById('recipientWallet').value;
            const amount = document.getElementById('amount').value;
            const description = document.getElementById('description').value;

            // Send the transaction data to the server
            const response = await fetch('send_transaction.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ recipientWallet, amount, description })
            });

            const data = await response.json();
            const transactionMessage = document.getElementById('transactionMessage');
            transactionMessage.classList.remove('d-none');

            if (data.status === 'success') {
                transactionMessage.classList.add('alert-success');
                transactionMessage.textContent = 'Transaction successful!';
            } else {
                transactionMessage.classList.add('alert-danger');
                transactionMessage.textContent = 'Transaction failed: ' + data.message;
            }
        });
    </script>
</body>
</html>
