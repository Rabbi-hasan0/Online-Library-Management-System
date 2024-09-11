<?php
include("data_class.php");

// Sanitize and validate the deletebookid to ensure it is a number
$deletebookid = intval($_GET['deletebookid']);

if ($deletebookid > 0) {
    // Create an instance of the data class and call the deletebook method
    $obj = new data();
    $obj->setconnection();
    
    if ($obj->deletebook($deletebookid)) {
        header("Location:admin_service_dashboard.php?msg=Book deleted successfully");
    } else {
        header("Location:admin_service_dashboard.php?msg=Failed to delete book");
    }
} else {
    header("Location:admin_service_dashboard.php?msg=Invalid book ID");
}
?>
