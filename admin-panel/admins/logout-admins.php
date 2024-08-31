<?php
// Start the session
session_start();
// Clear all session variables
session_unset();
// Destroy the session
session_destroy();
// Redirect to a login page or homepage
header("Location: login-admins.php");
exit();