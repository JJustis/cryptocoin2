<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Crypto Mining Control Panel">
    <title>Crypto Mining Control Panel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Montserrat', sans-serif;
            color: #1A1A1D;
        }
        header {
            background-color: #1A1A1D;
            color: white;
            padding: 20px 0;
        }
        header h1 {
            font-size: 2.5rem;
            margin: 0;
        }
        .navbar {
            background-color: #1A1A1D;
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: #FFFFFF;
            font-weight: 600;
        }
        .hero-section {
            background: linear-gradient(135deg, #1A1A1D 60%, #F2A900 40%);
            color: white;
            text-align: center;
            padding: 100px 20px;
        }
        .hero-section h2 {
            font-size: 3rem;
            font-weight: bold;
        }
        .hero-section p {
            font-size: 1.25rem;
        }
        .btn-primary {
            background-color: #F2A900;
            border: none;
        }
        .btn-primary:hover {
            background-color: #cf8d00;
        }
        .footer {
            background-color: #1A1A1D;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .info-panel {
            background-color: #FFFFFF;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container text-center">
            <h1>Crypto Mining Control Panel</h1>
        </div>
    </header>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg"><a  href="https://betahut.bounceme.net/bitcoin/transaction.php" >
	<button type="button" class="btn btn-primary" >Send Tokens</button>
      </a>
</div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h2>Welcome to the Bitcoin Mining Control Panel</h2>
            <p>Manage your wallet, mine cryptocurrencies, and track real-time statistics.</p>
<button class="btn btn-primary btn-lg" onclick="window.location.href='signup.php'">Get Started</button>
        </div>
    </section>

    <!-- Wallet & Mining Control Section -->
    <div class="container">
        <div class="info-panel">
            <h3 class="text-center">Link Your Wallet and Start Mining</h3>
            <div class="row">
                <div class="col-md-6">
                    <input type="text" id="walletAddress" class="form-control" placeholder="Enter your wallet address">
                    <button id="linkWallet" class="btn btn-primary mt-3">Link Wallet</button>
                    <div id="walletMessage" class="alert alert-info mt-3 d-none">Wallet linked successfully!</div>
                </div>
                <div class="col-md-6 text-end">
                    <button id="startMining" class="btn btn-success">Start Mining</button>
                    <button id="stopMining" class="btn btn-danger">Stop Mining</button>
                    <p class="network-status mt-3" id="networkStatus">Network: Not Connected</p>
                </div>
            </div>
        </div>

        <!-- Mining Statistics Panel -->
        <div class="info-panel">
            <h3 class="text-center">Mining Statistics</h3>
            <div class="row">
                <div class="col-md-3">
                    <h5>Hash Rate</h5>
                    <p id="hashingRate">0 H/s</p>
                </div>
                <div class="col-md-3">
                    <h5>Pending Transactions</h5>
                    <p id="pendingTransactions">0</p>
                </div>
                <div class="col-md-3">
                    <h5>Current Block</h5>
                    <p id="currentBlock">N/A</p>
                </div>
                <div class="col-md-3">
                    <h5>Wallet Rewards</h5>
                    <p id="walletRewards">0 Coins</p>
                </div>
            </div>
        </div>

        <!-- Blockchain Info Panel -->
        <div class="info-panel">
            <h3 class="text-center">Blockchain Information</h3>
            <pre id="blockchainDisplay">No blockchain data available</pre>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 Bitcoin Mining Control Panel</p>
    </footer>

    <!-- Bootstrap JS and Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- CryptoJS Library for Hashing -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
       <script>
// Example JavaScript Code for Handling JSON Parsing Errors
async function loginUser() {
    try {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        console.log("Login attempt with username:", username, "and password:", password);

        const response = await fetch('login_user.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, password })
        });

        const rawText = await response.text();
        console.log("Raw Response:", rawText);  // Log the raw response

        const data = JSON.parse(rawText);
        console.log("Parsed Data:", data);  // Log the parsed response for inspection

        if (data.status === 'success') {
            // Redirect to dashboard or another page
            window.location.href = 'user_dashboard.php';
        } else {
            alert(data.message);  // Display the error message from the response
        }
    } catch (error) {
        console.error("Error during login:", error);
    }
}

    </script>
    <!-- JavaScript Logic for Mining and Blockchain -->
    <script>
        let isMining = false;
        let walletAddress = '';
        let hashrate = 0;
        let hashes = 0;
        let pendingTransactions = 0;
        const difficulty = 4; // Adjust the mining difficulty here

        // Event listener to link the wallet address
        document.getElementById('linkWallet').addEventListener('click', () => {
            walletAddress = document.getElementById('walletAddress').value.trim();
            const walletMessage = document.getElementById('walletMessage');
            if (walletAddress) {
                walletMessage.textContent = 'Wallet linked successfully!';
                walletMessage.classList.remove('d-none', 'alert-danger');
                walletMessage.classList.add('alert-success');
            } else {
                walletMessage.textContent = 'Please enter a valid wallet address.';
                walletMessage.classList.remove('d-none', 'alert-success');
                walletMessage.classList.add('alert-danger');
            }
        });

        // Event listeners for start/stop mining buttons
        document.getElementById('startMining').addEventListener('click', () => startMining());
        document.getElementById('stopMining').addEventListener('click', () => stopMining());

        async function startMining() {
            if (isMining || !walletAddress) {
                alert('Please link your wallet before starting mining.');
                return;
            }
            isMining = true;
            console.log('Mining started...');
            document.getElementById('hashingRate').innerText = 'Calculating...';
            hashes = 0;
            calculateHashRate();
            mine();
        }

        function stopMining() {
            isMining = false;
            console.log('Mining stopped.');
            document.getElementById('hashingRate').innerText = '0 H/s';
        }

        // Calculate real hash rate by counting hashes performed in a time interval
        function calculateHashRate() {
            setInterval(() => {
                if (!isMining) return;
                document.getElementById('hashingRate').innerText = `${hashes} H/s`;
                hashes = 0;  // Reset the count for the next second
            }, 1000);  // Update the rate every second
        }

async function mine() {
    if (!isMining) return;

    try {
        // Fetch pending transactions to include in the block
        const transactionsResponse = await fetch('get_pending_transactions.php');
        const transactionsData = await transactionsResponse.json();
        
        if (transactionsData.transactions && transactionsData.transactions.length > 0) {
            pendingTransactions = transactionsData.transactions.length;

            // Update the UI
            document.getElementById('pendingTransactions').innerText = pendingTransactions;

            // Start measuring time and hash count for calculating hash rate
            let hashCount = 0;
            const startTime = performance.now();

            // Generate a new block with transactions
            const newBlock = await createBlock(transactionsData.transactions, (nonce) => {
                // Increment hash count for each hash attempt during Proof-of-Work
                hashCount++;
            });
            
            const endTime = performance.now();  // End time after block is mined

            // Calculate real hash rate (hashes per second)
            const timeTaken = (endTime - startTime) / 1000;  // Convert to seconds
            const hashRate = (hashCount / timeTaken).toFixed(2);  // Hashes per second
            document.getElementById('hashingRate').innerText = `${hashRate} H/s`;

            console.log(`New block generated with hash rate: ${hashRate} H/s`);
            console.log('New block generated:', newBlock);

            // Submit the mined block to the server
            const response = await fetch('mine.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ minedBlock: newBlock })
            });
            const data = await response.json();
            console.log('Block submission status:', data.message);
        } else {
            console.log('No transactions available or incorrect data structure.');
            document.getElementById('pendingTransactions').innerText = '0';
        }
    } catch (error) {
        console.error('Error during mining:', error);
    }

    if (isMining) {
        setTimeout(mine, 5000);  // Continue mining if still active
    }
}

// Updated createBlock function to include a callback for tracking hashes
async function createBlock(transactions, hashCallback) {
    const previousBlockHash = await getLatestBlockHash();  // Fetch from server
    let nonce = 0;  // Start nonce value
    let hash = '';  // Block hash

    // Calculate the hash until it meets the difficulty requirements (Proof-of-Work)
    do {
        nonce++;
        hash = await calculateHash(transactions, previousBlockHash, nonce);
        
        // Call the hash callback (used to count hashes for hash rate calculation)
        if (typeof hashCallback === 'function') {
            hashCallback(nonce);
        }
    } while (!hash.startsWith('0'.repeat(difficulty)));  // Adjust difficulty as needed

    // Return the new block with all necessary information
    return {
        transactions: transactions,
        timestamp: new Date().toISOString(),
        nonce: nonce,
        previousHash: previousBlockHash,
        hash: hash  // The calculated hash of this block
    };
}

// Function to calculate the hash of a block using SHA-256
async function calculateHash(transactions, previousHash, nonce) {
    const blockString = JSON.stringify(transactions) + previousHash + nonce;
    const encoder = new TextEncoder();
    const data = encoder.encode(blockString);

    const hashBuffer = await crypto.subtle.digest('SHA-256', data);  // Compute the hash using SHA-256
    const hashArray = Array.from(new Uint8Array(hashBuffer));  // Convert buffer to byte array
    const hashHex = hashArray.map(byte => byte.toString(16).padStart(2, '0')).join('');  // Convert to hex string

    return hashHex;
}

// Function to check network status and update the UI
async function checkNetworkStatus() {
    try {
        // Try to fetch the latest block hash to check network connection
        const response = await fetch('get_latest_block_hash.php');
        const data = await response.json();

        // If the response is successful and contains a hash, the network is connected
        if (data.status === 'success' && data.hash) {
            document.getElementById('networkStatus').textContent = 'Network: Connected';
            document.getElementById('networkStatus').classList.remove('text-danger');
            document.getElementById('networkStatus').classList.add('text-success');
        } else {
            // If the response is not successful, show as not connected
            document.getElementById('networkStatus').textContent = 'Network: Not Connected';
            document.getElementById('networkStatus').classList.remove('text-success');
            document.getElementById('networkStatus').classList.add('text-danger');
        }
    } catch (error) {
        console.error('Error checking network status:', error);
        document.getElementById('networkStatus').textContent = 'Network: Not Connected';
        document.getElementById('networkStatus').classList.remove('text-success');
        document.getElementById('networkStatus').classList.add('text-danger');
    }
}

// Call this function to check network status on page load
checkNetworkStatus();

// Periodically check network status every 5 seconds
setInterval(checkNetworkStatus, 5000);

        // Function to fetch the latest block's hash from the server
        async function getLatestBlockHash() {
            try {
                const response = await fetch('get_latest_block_hash.php');
                const data = await response.json();
                if (data.status === 'success') {
                    console.log('Latest block hash fetched:', data.hash);
                    document.getElementById('currentBlock').innerText = data.hash;
                    return data.hash;
                } else {
                    console.warn("No previous block hash found, using default genesis hash.");
                    return '0x0000000000000000000000000000000000000000000000000000000000000000';  // Genesis hash
                }
            } catch (error) {
                console.error("Error fetching the latest block hash:", error);
                return '0x0000000000000000000000000000000000000000000000000000000000000000';  // Genesis hash fallback
            }
        }



// Function to fetch and display blockchain data
async function updateBlockchain() {
    try {
        // Fetch the blockchain data from the server
        const response = await fetch('get_chain.php');
        const rawText = await response.text();
        console.log("Raw Response from get_chain.php:", rawText);  // Log the raw response for debugging

        // Parse the response into JSON
        const data = JSON.parse(rawText);

        // Check if the response status is success
        if (data.status === 'success') {
            const chain = data.chain;

            // Update the blockchain display with the formatted JSON data
            const blockchainDisplay = document.getElementById('blockchainDisplay');
            blockchainDisplay.innerText = JSON.stringify(chain, null, 2);

            // Check if any blocks are present
            if (chain.length === 0) {
                blockchainDisplay.innerText = "No blockchain data available. Create the genesis block first.";
            }
        } else {
            console.error('Failed to fetch blockchain data:', data.message);
        }
    } catch (error) {
        console.error('Error fetching blockchain data:', error);
    }

    // Fetch the blockchain data again after a delay (e.g., for long polling)
    setTimeout(updateBlockchain, 5000);
}

// Fetch blockchain data on page load
updateBlockchain();

    </script>
</body>
</html>
