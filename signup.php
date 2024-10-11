<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Signup for a Cryptocurrency Wallet">
    <title>Signup for Wallet</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .form-container {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            background-color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Signup for a Cryptocurrency Wallet</h1>

        <!-- Signup Form -->
        <div class="form-container">
            <form id="signupForm">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email (Optional)</label>
                    <input type="email" class="form-control" id="email">
                </div>
                <button type="submit" class="btn btn-primary">Signup</button>
                <div id="signupMessage" class="alert mt-3 d-none"></div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle form submission
        document.getElementById('signupForm').addEventListener('submit', async (event) => {
            event.preventDefault(); // Prevent default form submission

            // Get form values
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const email = document.getElementById('email').value;

            // Send the signup data to the server
            const response = await fetch('register_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username, password, email })
            });

            const data = await response.json();
            const signupMessage = document.getElementById('signupMessage');
            signupMessage.classList.remove('d-none');

            if (data.status === 'success') {
                signupMessage.classList.add('alert-success');
                signupMessage.textContent = 'Signup successful! Your wallet address: ' + data.walletAddress;
            } else {
                signupMessage.classList.add('alert-danger');
                signupMessage.textContent = 'Signup failed: ' + data.message;
            }
        });
    </script>
</body>
</html>
