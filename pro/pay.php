<?php
require_once 'session.php';
require_once '../constants.php';

// Check if amount and email are set in session
if (!isset($_SESSION['amount'], $_SESSION['email'])) {
    @session_destroy();
    header("Location: ../");
    exit;
}

// Initialize cURL
$pay = curl_init();

// Set email and amount for the transaction
$email = $_SESSION['email'];
$amount = $_SESSION['amount'] * 100; // Amount in cents (assuming the amount is in KES)

// Configure cURL options
curl_setopt_array($pay, array(
    CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode([
        'amount' => $amount,
        'email' => $email,
    ]),
    CURLOPT_HTTPHEADER => [
        "authorization: Bearer sk_test_2308a0de5d3b058a2280b0eaf86dd93c15af0479", // Replace with your test secret key
        "content-type: application/json",
        "cache-control: no-cache"
    ],
));

// Execute cURL request
$response = curl_exec($pay);
$err = curl_error($pay);

// Close cURL
curl_close($pay);

// Check for errors
if ($err) {
    header("Location: individual.php?page=pay&error=payment&access=0");
    exit();
}

// Decode the response
$tranx = json_decode($response);

// Check if transaction status is not successful or empty
if (!$tranx->status or empty($tranx->status)) {
    // There was an error from the API
    header("Location: individual.php?page=pay&error=payment&access=1");
    exit();
}

// Redirect to the payment page
header('Location: ' . $tranx->data->authorization_url);
exit();
?>
