<?php

include("data_class.php");

// Sanitize user inputs
$book = htmlspecialchars(trim($_POST['book']));
$userselect = htmlspecialchars(trim($_POST['userselect']));
$days = intval($_POST['days']);  // Ensure $days is an integer

// Get the current date and return date in the proper format (Y-m-d)
$getdate = date("Y-m-d");  // Current date in Y-m-d format
$returnDate = date("Y-m-d", strtotime('+' . $days . ' days'));  // Return date after adding $days

// Check if any field is empty
if (empty($book) || empty($userselect) || empty($days)) {
    echo "All fields are required.";
    exit();
}

// Create object of data class and call the issuebook method
$obj = new data();
$obj->setconnection();
if ($obj->issuebook($book, $userselect, $days, $getdate, $returnDate)) {
    echo "Book issued successfully!";
} else {
    echo "Error issuing the book!";
}
?>
