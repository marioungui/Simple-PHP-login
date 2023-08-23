<?php
// Start session to store user login status
session_start();

function authenticateUser($username, $password) {
    try {
        // Establish a database connection using PDO
        $dsn = 'mysql:host=localhost;dbname=tomapp';
        $pdo = new PDO($dsn, 'root', '');

        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the SQL statement to fetch user information based on the username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);

        // Fetch the user data (if found) into an associative array
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password
        if ($userData && password_verify($password, $userData['password_hash'])) {
            // Password is correct, set the user info in $_SESSION
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['username'] = $userData['username'];
            $_SESSION['email'] = $userData['email'];
            $_SESSION['user_role'] = $userData['role'];
            $_SESSION['full_name'] = $userData['full_name'];
            $_SESSION['business'] = $userData['business'];
            $_SESSION['user_since'] = $userData['created_at'];
            // Add any other relevant user information you want to store in $_SESSION

            // Return true to indicate successful authentication
            return true;
        } else {
            // Incorrect credentials or user not found, return false
            return false;
        }
    } catch (PDOException $e) {
        // Handle any database connection or query errors
        die("Error: " . $e->getMessage());
    }
}

// Check if the user is logged in
$loggedIn = authenticateUser($_POST['username'],$_POST['password']); 

if ($loggedIn) {
    // If user is logged in, redirect to a protected page
    header("Location: index.php");
    exit();
} else {
    // If user is not logged in, redirect to the login page
    header("Location: login.php?password=false");
    exit();
}
?>
