<?php

include("data_class.php");

// Use POST method and sanitize input
$login_email = filter_var($_POST['login_email'] ?? null, FILTER_SANITIZE_EMAIL);
$login_password = htmlspecialchars(trim($_POST['login_password'] ?? null));

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
    header("Location: index.php?ademailmsg=" . urlencode($emailmsg) . "&adpasdmsg=" . urlencode($pasdmsg));
    exit();  // Ensure the script stops after redirection
} else {
    // Initialize data class and try admin login
    $obj = new data();
    $obj->setconnection();

    // Validate login credentials
    if ($obj->adminLogin($login_email, $login_password)) {
        // Redirect to the dashboard or desired page upon successful login
        header("Location: admin_dashboard.php");
        exit();  // Ensure the script stops after redirection
    } else {
        // Redirect back with a login failed message
        header("Location: index.php?ademailmsg=Invalid email or password");
        exit();  // Ensure the script stops after redirection
    }
}
?>
