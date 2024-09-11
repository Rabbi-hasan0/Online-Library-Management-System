<?php

include("data_class.php");

// Start the session
session_start();

// Check if POST data exists
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $login_email = filter_var(trim($_POST['login_email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $login_password = htmlspecialchars(trim($_POST['login_password'] ?? ''));

    // Check for empty email or password and store error messages
    $emailmsg = "";
    $pasdmsg = "";

    if (empty($login_email)) {
        $emailmsg = "Email is empty";
    }
    if (empty($login_password)) {
        $pasdmsg = "Password is empty";
    }

    // If there are any errors, redirect back to the index.php page with error messages
    if (!empty($emailmsg) || !empty($pasdmsg)) {
        header("Location: index.php?emailmsg=" . urlencode($emailmsg) . "&pasdmsg=" . urlencode($pasdmsg));
        exit();  // Ensure the script stops after redirection
    }

    // If both email and password are provided
    if (!empty($login_email) && !empty($login_password)) {
        // Initialize data class and try login
        $obj = new data();
        $obj->setconnection();
        
        // Assuming userLogin() validates login credentials
        if ($obj->userLogin($login_email, $login_password)) {
            // If login is successful, redirect to a dashboard or home page
            header("Location: dashboard.php");
            exit();
        } else {
            // If login fails, redirect back to the login page with an error message
            $loginError = "Invalid email or password";
            header("Location: index.php?loginError=" . urlencode($loginError));
            exit();
        }
    }
} else {
    // If the request method is not POST, redirect to index.php
    header("Location: index.php");
    exit();
}
?>
