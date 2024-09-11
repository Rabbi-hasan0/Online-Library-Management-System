<?php

include("data_class.php");

// Sanitize and validate inputs
$userid = htmlspecialchars(trim($_GET['userid']));
$bookid = htmlspecialchars(trim($_GET['bookid']));

// Check if the required parameters are present
if (empty($userid) || empty($bookid)) {
    echo "User ID and Book ID are required.";
    exit();
}

// Validate that $userid and $bookid are integers (assuming they should be integers)
if (!filter_var($userid, FILTER_VALIDATE_INT) || !filter_var($bookid, FILTER_VALIDATE_INT)) {
    echo "Invalid User ID or Book ID.";
    exit();
}

// Create an instance of the data class and call the requestbook method
$obj = new data();
$obj->setconnection();
if ($obj->requestbook($userid, $bookid)) {
    echo "Book request processed successfully!";
} else {
    echo "Error processing the book request.";
}

?>
