<?php
// Start session to check if the user is logged in (you can modify this part based on your authentication mechanism)
session_start();

// Check if the user is logged in (replace 'user_id' with the actual session variable holding user ID from your authentication mechanism)
$loggedIn = isset($_SESSION['user_id']);

if (!$loggedIn) {
    // If the user is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

/**
 * Summary of isAdmin
 * @return bool
 */
function isAdmin() {
    if ($_SESSION['user_role'] == "admin") {
        return true;
    }
    else {
        return false;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Website</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Add any additional meta tags you want to include -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add any additional CSS files you want to include -->
</head>
<body>
