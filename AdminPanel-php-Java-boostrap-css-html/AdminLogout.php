<?php
session_start();

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Check if user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();
}

// Redirect to login page after logout
header("Location: AdminLogin.php");
exit();
?>
