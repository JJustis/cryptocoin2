<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="User Dashboard for Cryptocurrency Wallet">
    <title>User Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 30px;
        }
        .wallet-info, .transaction-info {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            background-color: #ffffff;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">User Dashboard</h1>

        <!-- Wallet Information -->
        <div class="wallet-info">
            <h3>Wallet Information</h3>
            <p><strong>Wallet Address:</strong> <span id="walletAddress">N/A</span></p>
            <p><strong>Public Key:</strong> <span id="publicKey">N/A</span></p>
            <p><strong>Balance:</strong> <span id="balance">0</span> Tokens</p>
        </div>

        <!-- Transaction History -->
        <div class="transaction-info">
            <h3>Transaction History</h3>
            <ul id="transactionList" class="list-group"></ul>
        </div>
    </div>

    <!-- Bootstrap JS and Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fetch user wallet data on page load (Replace with actual AJAX request to fetch user data)
        document.addEventListener('DOMContentLoaded', async () => {
            const response = await fetch('get_user_data.php');
            const data = await response.json();

            if (data.status === 'success') {
                document.getElementById('walletAddress').innerText = data.walletAddress;
                document.getElementById('publicKey').innerText = data.publicKey;
                document.getElementById('balance').innerText = data.balance;

                const transactionList = document.getElementById('transactionList');
                data.transactions.forEach(transaction => {
                    const listItem = document.createElement('li');
                    listItem.className = 'list-group-item';
                    listItem.textContent = `${transaction.type}: ${transaction.amount} Tokens to/from ${transaction.to_from}`;
                    transactionList.appendChild(listItem);
                });
            }
        });
    </script>
</body>
</html>
