<?php

include("data_class.php");

// Check if POST data exists
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_email = $_POST['login_email'] ?? null;
    $login_password = $_POST['login_password'] ?? null;

    // Check for empty email or password
    if (empty($login_email) || empty($login_password)) {
        $emailmsg = "";
        $pasdmsg = "";

        if (empty($login_email)) {
            $emailmsg = "Email is empty";
        }
        if (empty($login_password)) {
            $pasdmsg = "Password is empty";
        }

        // Redirect back to the index.php page with error messages
        header("Location: index.php?emailmsg=$emailmsg&pasdmsg=$pasdmsg");
        exit();  // Ensure the script stops after redirection
    }

    // If both email and password are provided
    if (!empty($login_email) && !empty($login_password)) {
        // Initialize data class and try login
        $obj = new data();
        $obj->setconnection();
        
        // Assuming userLogin() handles login validation and session setting
        $obj->userLogin($login_email, $login_password);
    }
} else {
    // If the request method is not POST, redirect to index.php
    header("Location: index.php");
    exit();
}
?>

