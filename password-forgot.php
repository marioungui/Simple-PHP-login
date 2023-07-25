<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
// Include your database connection file (replace 'your_db_host', 'your_db_username', 'your_db_password', and 'your_db_name' with actual values)
$dsn = 'mysql:host=localhost;dbname=tomapp';
$username = 'root';
$password = '';

// Function to generate a random token
function generateToken() {
    return bin2hex(random_bytes(32));
}

// Function to send the password reset email (you can use PHPMailer or other libraries for sending emails)
function sendResetEmail($email, $token) {
    $mail = new PHPMailer(true);
    
    // Set up SMTP for sending the email
    // $mail->isSMTP();
    // $mail->Host = 'smtp.example.com'; // Replace with your SMTP host
    // $mail->SMTPAuth = true;
    // $mail->Username = 'your_smtp_username'; // Replace with your SMTP username
    // $mail->Password = 'your_smtp_password'; // Replace with your SMTP password
    // $mail->SMTPSecure = 'tls'; // Set the encryption type (tls or ssl)
    // $mail->Port = 587; // Set the SMTP port (typically 587 for tls)

    // Set the From and Reply-To addresses
    $mail->setFrom('from@example.com', 'Your Website'); // Replace with your email and website name
    $mail->addReplyTo('reply-to@example.com', 'Your Website'); // Replace with your reply-to email and website name

    // Set the recipient
    $mail->addAddress($email);

    // Set email subject and body
    $mail->Subject = 'Password Reset Request';
    $mail->Body = "Click the link to reset your password: https://".$_SERVER['SERVER_NAME']."/password-reset.php?token=$token";

    // Send the email
    if (!$mail->send()) {
        // If there's an error in sending the email, you can handle it here (e.g., display an error message)
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize the input (you can add more validation as needed)
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Check if the email exists in the database (you should have a users table in your database)
    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the email exists in the users table
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Generate a random token
            $token = generateToken();

            // Save the token and its expiration time in the database (you should have a password_reset_tokens table in your database)
            $expireTime = time() + 3600; // Token will expire in 1 hour
            $stmt = $pdo->prepare("INSERT INTO password_reset_tokens (email, token, expire_time) VALUES (:email, :token, :expire_time)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':expire_time', $expireTime);
            $stmt->execute();

            // Send the password reset email to the user
            sendResetEmail($email, $token);

            // Redirect the user to a confirmation page or show a success message
            header("Location: password-forgot-confirm.php");
            exit();
        } else {
            // Email not found in the database, redirect the user back to the password-forgot.php page with an error message
            header("Location: password-forgot.php?error=1");
            exit();
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Forgot Password</h2>
                <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
                    <div class="alert alert-danger" role="alert">
                        Email not found. Please check your email address.
                    </div>
                <?php endif; ?>
                <form action="password-forgot.php" method="POST">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
