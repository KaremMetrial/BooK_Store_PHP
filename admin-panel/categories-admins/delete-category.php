<?php
require "../layouts/header.php";
require "../../config/config.php";
require "../../config/helper.php";

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id = intval($_GET["id"]);

    $getCategory = read($conn, 'categories', ['id' => $id]);

    if ($getCategory) {

        $imagePath = "../../categories/image/" . $getCategory[0]['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        } else {
            error_log("Image not found: " . $imagePath);
        }

        // Delete the category from the database
        $deleteCategory = delete($conn, 'categories', ['id' => $id]);
        if ($deleteCategory) {
            header("location: show-categories.php");
            exit();
        } else {
            error_log("Failed to delete category with ID: $id");
        }
    } else {
        error_log("Category not found with ID: $id");
    }

    // Redirect back to categories page
    header("location: show-categories.php");
    exit();
} else {
    header("location: show-categories.php");
    exit();
}
