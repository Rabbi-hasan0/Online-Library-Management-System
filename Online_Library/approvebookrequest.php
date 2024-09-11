<?php
include("data_class.php");

// Sanitize and validate inputs
$request = htmlspecialchars(trim($_GET['reqid']));
$book = htmlspecialchars(trim($_GET['book']));
$userselect = htmlspecialchars(trim($_GET['userselect']));
$days = intval($_GET['days']);  // Ensure days is an integer

// Check if all required data is provided
if (empty($request) || empty($book) || empty($userselect) || empty($days)) {
    echo "Required fields are missing.";
    exit();
}

// Current date and return date calculation
$getdate = date("Y-m-d");  // Use Y-m-d for better consistency in database
$returnDate = date("Y-m-d", strtotime('+' . $days . ' days')); // Calculate return date

// Create data object and approve book request
$obj = new data();
$obj->setconnection();

// Call the issuebookapprove function
if ($obj->issuebookapprove($book, $userselect, $days, $getdate, $returnDate, $request)) {
    echo "Book request approved successfully!";
} else {
    echo "Failed to approve book request!";
}
?>
