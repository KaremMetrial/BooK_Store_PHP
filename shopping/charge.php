<?php
require "../config/config.php";
require "../config/helper.php";
require "../includes/header.php";
require_once "../vendor/autoload.php";
if (isset($_POST['email'])) {
    // Initialize the StripeClient with the secret key
    $stripe = new \Stripe\StripeClient($secret_key);

    // Create a charge using the StripeClient object
    $charge = $stripe->charges->create([
        'source' => $_POST['stripeToken'], // The token generated by Stripe.js
        'amount' => $_SESSION['summary_total_price'] * 100, // Total amount in cents
        'currency' => 'usd',
    ]);

    if (empty($_POST['email']) || empty($_POST['username']) || empty($_POST['fname']) || empty($_POST['lname'])) {
        echo "Please fill out all the fields";
    } else {

        $email = $_POST['email'];
        $username = $_POST['username'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $price = $_SESSION['summary_total_price'];
        $token = $_POST['stripeToken'];
        $user_id = $_SESSION['user_id'];
        $insertData = insert($conn, 'orders', ['email' => $email, 'username' => $username, 'fname' => $fname, 'lname' => $lname, "token" => $token, 'price' => $price, 'user_id' => $user_id]);

    }
}

?>