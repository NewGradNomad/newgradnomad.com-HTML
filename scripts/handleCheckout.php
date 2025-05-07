<?php
//includes database connection
require_once '../components/db_connect.php';
require_once '../vendor/autoload.php';
require_once '../components/secrets.php';
require_once '../components/domain.php';
//includes session info
session_start();

// Validate required session data
if (!isset($_SESSION['listingNumber']) || !isset($_SESSION['orderTotal'])) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Missing required session data',
        'message' => 'Please try again or contact support'
    ]);
    exit();
}

try {
    $stripe = new \Stripe\StripeClient($stripeSecretKey);
    
    if (!isset($domain)) {
        throw new Exception('Domain configuration is missing');
    }

    $YOUR_DOMAIN = $domain;
    $checkout_session = $stripe->checkout->sessions->create([
        'ui_mode' => 'embedded',
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'Job Listing',
                    'description' => 'Listing ID: ' . $_SESSION['listingNumber'],
                ],
                'unit_amount' => intval($_SESSION['orderTotal']),
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'return_url' => $YOUR_DOMAIN . '/pages/success?session_id={CHECKOUT_SESSION_ID}',
    ]);

    if (!$checkout_session || !$checkout_session->client_secret) {
        throw new Exception('Failed to create checkout session');
    }

    echo json_encode(['clientSecret' => $checkout_session->client_secret]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
