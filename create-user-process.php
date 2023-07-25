<?php
// Start session to check if the user is logged in (you can modify this part based on your authentication mechanism)
session_start();

function isAdmin() {
    if ($_SESSION['user_role'] == "admin") {
        return true;
    }
    else {
        return false;
    }
}

if (!isAdmin()) {
    // If the user is not logged in as admin, redirect to the login page or an error page
    header("Location: login.php");
    exit();
}

// Process the form data and create the user
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize input data (you can use additional validation as per your requirements)
    $username = filter_var($_POST["username"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role = $_POST["role"]; // Make sure the role is one of the allowed roles (admin, supervisor, user)
    $business = filter_var($_POST['business'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $full_name = filter_var($_POST['full_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);


    try {
        // Establish a database connection using PDO
        $dsn = 'mysql:host=localhost;dbname=tomapp';
        $pdo = new PDO($dsn, 'root', '');

        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the SQL statement to insert the user
        $stmt = $pdo->prepare("INSERT INTO users (username, email, full_name, business, password_hash, role) VALUES (:username, :email, :full_name, :business, :password, :role)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':business', $business);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);

        // Execute the SQL statement
        if ($stmt->execute()) {
            // User creation successful, redirect to a success page or any other appropriate action
            header("Location: create-user-success.php");
            exit();
        } else {
            // Error occurred during user creation, handle the error (e.g., display an error message)
            $error_message = "Error creating user.";
        }
    } catch (PDOException $e) {
        // Handle any database connection or query errors
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Error</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Error</h2>
        <p><?php echo $error_message; ?></p>
        <a href="create_user.php" class="btn btn-primary">Back to Create User</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
