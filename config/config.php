<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "bookstore";
$secret_key = "sk_test_51PsuGNRxtuGINg5hCLEl6YrWtpJ8hlNdrXfWsWyI37UwL4IVh1PoKpz3GSBa04xNTPHoKdsrbqTnrqNbCk7iKA6b00tFxBQ7KC";
try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}