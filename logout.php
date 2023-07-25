<?php
// Start session to destroy the user session and log them out
session_start();

// Destroy the session and redirect to the login page
session_destroy();
header("Location: login.php");
exit();
?>
