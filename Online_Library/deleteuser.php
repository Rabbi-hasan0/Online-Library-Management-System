<?php
include("data_class.php");

$deleteuser = isset($_GET['useriddelete']) ? intval($_GET['useriddelete']) : 0;

if ($deleteuser > 0) {
    // Create an instance of data class and set the connection
    $obj = new data();
    $obj->setconnection();
    
    // Call the delete method
    if ($obj->deleteuserdata($deleteuser)) {
        header("Location: admin_service_dashboard.php?msg=User deleted successfully");
    } else {
        header("Location: admin_service_dashboard.php?msg=Failed to delete user");
    }
} else {
    header("Location: admin_service_dashboard.php?msg=Invalid user ID");
}
?>
