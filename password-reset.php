<?php
// Include your database connection file (replace 'your_db_host', 'your_db_username', 'your_db_password', and 'your_db_name' with actual values)

// Function to check if the reset token is valid and not expired
function isTokenValid($token) {
    $dsn = 'mysql:host='. DB_HOST .';dbname='. DB_NAME;
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the token exists and is not expired
        $current_time = time();
        $stmt = $pdo->prepare("SELECT email, expire_time FROM password_reset_tokens WHERE token = :token AND expire_time > :current_time");
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':current_time', $current_time);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['email'];
        } else {
            return false;
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Function to update the user's password
function updatePassword($email, $newPassword) {
    $dsn = 'mysql:host='. DB_HOST .';dbname='. DB_NAME;
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Update the user's password in the users table (replace 'users' with your user table name)
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password_hash = :password WHERE email = :email");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

         // Delete the associated token from the password_reset_tokens table
        $stmt = $pdo->prepare("DELETE FROM password_reset_tokens WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize the input (you can add more validation as needed)
    $token = $_POST['token'];
    $password = $_POST['password'];

    // Check if the token is valid and get the associated email
    $email = isTokenValid($token);

    if ($email) {
        // Token is valid, update the user's password
        updatePassword($email, $password);

        // Redirect the user to the login page or a success page
        header("Location: login.php?reset=success");
        exit();
    } else {
        // Token is invalid or expired, show an error message or redirect to a failure page
        header("Location: login.php?reset=invalid");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cambio de contraseña</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Cambio de contraseña</h2>
        <form action="password-reset.php" method="POST">
            <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
            <div class="form-group">
                <label for="password">Nueva contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Cambiar contraseña</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
