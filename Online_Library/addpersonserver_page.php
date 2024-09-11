<?php
include("data_class.php");

// Sanitize inputs to prevent SQL injection and XSS attacks
$addnames = htmlspecialchars(trim($_POST['addname']));
$addpass = htmlspecialchars(trim($_POST['addpass']));
$addemail = htmlspecialchars(trim($_POST['addemail']));
$type = htmlspecialchars(trim($_POST['type']));

// Check if any of the fields are empty
if (empty($addnames) || empty($addpass) || empty($addemail) || empty($type)) {
    echo "All fields are required.";
    exit();
}

// Hash the password for security before storing it
$hashed_pass = password_hash($addpass, PASSWORD_BCRYPT);

// Create an instance of the data class and call the addnewuser method
$obj = new data();
$obj->setconnection();

// Add the new user, passing the hashed password
if ($obj->addnewuser($addnames, $hashed_pass, $addemail, $type)) {
    echo "User successfully added!";
} else {
    echo "Error adding user!";
}
?>
