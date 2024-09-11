<?php
// addserver_page.php
include("data_class.php");

// Fetch form data
$bookname = htmlspecialchars($_POST['bookname']);
$bookdetail = htmlspecialchars($_POST['bookdetail']);
$bookaudor = htmlspecialchars($_POST['bookaudor']);
$bookpub = htmlspecialchars($_POST['bookpub']);
$branch = htmlspecialchars($_POST['branch']);
$bookprice = floatval($_POST['bookprice']);
$bookquantity = intval($_POST['bookquantity']);

// File upload handling
if (move_uploaded_file($_FILES["bookphoto"]["tmp_name"], "uploads/" . $_FILES["bookphoto"]["name"])) {

    // Get file name to store in the database
    $bookpic = "uploads/" . $_FILES["bookphoto"]["name"];

    // Create object of data class and call the addbook method
    $obj = new data();
    $obj->setconnection();

    // Add book details to the database
    if ($obj->addbook($bookpic, $bookname, $bookdetail, $bookaudor, $bookpub, $branch, $bookprice, $bookquantity)) {
        echo "Book successfully added!";
    } else {
        echo "Error adding book to the database!";
    }
} else {
    echo "File not uploaded";
}
?>
