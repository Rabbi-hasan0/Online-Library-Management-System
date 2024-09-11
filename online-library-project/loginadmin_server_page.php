<?php

include("data_class.php");

// Use POST instead of GET
$login_email = $_POST['login_email'] ?? null;
$login_password = $_POST['login_password'] ?? null;  // Correct the spelling of 'password'

if (empty($login_email) || empty($login_password)) {
    $emailmsg = "";
    $pasdmsg = "";

    if (empty($login_email)) {
        $emailmsg = "Email is empty";
    }
    if (empty($login_password)) {
        $pasdmsg = "Password is empty";
    }

    // Redirect back to the index.php page with admin-specific error messages
    header("Location: index.php?ademailmsg=$emailmsg&adpasdmsg=$pasdmsg");
    exit();  // Ensure the script stops after redirection
} elseif (!empty($login_email) && !empty($login_password)) {
    // Initialize data class and try admin login
    $obj = new data();
    $obj->setconnection();

    // Assuming adminLogin() handles admin login validation and session setting
    $obj->adminLogin($login_email, $login_password);
}
?>

