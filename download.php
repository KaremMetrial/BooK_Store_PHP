<?php
session_start();
require "config/config.php";
require "config/helper.php";

// Check if user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit();
}

// Fetch the cart items for the user
$selectp = read($conn, 'cart', ['user_id' => $_SESSION['user_id']]);

$zipname = 'bookstore.zip';
$zip = new ZipArchive;

// Attempt to create the ZIP file
if ($zip->open($zipname, ZipArchive::CREATE) === TRUE) {
    foreach ($selectp as $product) {
        // Assuming 'product_file' is the column in the 'cart' table that holds the file name
        $filePath = "books/" . $product['product_file'];

        // Check if the file exists before adding it to the zip
        if (file_exists($filePath)) {
            $zip->addFile($filePath, $product['product_file']);
        }
    }
    $zip->close();
} else {
    die("Error: Unable to create ZIP file.");
}

// Ensure the ZIP file was created before proceeding
if (file_exists($zipname)) {
    // Set headers to trigger file download
    header('Content-Type: application/zip');
    header('Content-disposition: attachment; filename=' . $zipname);
    readfile($zipname);

    // Optionally, delete the ZIP file after sending it
    unlink($zipname);
} else {
    die("Error: ZIP file not found.");
}

// Clear the cart after download
$deletep = delete($conn, 'cart', ['user_id' => $_SESSION['user_id']]);

// Redirect after download
header("location: index.php");
exit();
?>
