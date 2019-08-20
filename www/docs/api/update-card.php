<?php

include_once '../../includes/easyparliament/init.php';

if (!$THEUSER->loggedin()) {
    redirect('/api/');
}

$subscription = new MySociety\TheyWorkForYou\Subscription($THEUSER);
if (!$subscription->stripe) {
    redirect('/api/key');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $setup_intent = \Stripe\SetupIntent::create();
    header('Content-Type: application/json');
    print json_encode([
        'secret' => $setup_intent->client_secret,
    ]);
    exit;
}

MySociety\TheyWorkForYou\Utility\Session::start();
if (!Volnix\CSRF\CSRF::validate($_POST)) {
    print 'CSRF validation failure!';
    exit;
}

$payment_method = get_http_var('payment_method');
$payment_method = \Stripe\PaymentMethod::retrieve($payment_method);
$sub = $subscription->stripe;
$payment_method->attach(['customer' => $sub->customer->id]);
\Stripe\Customer::update($sub->customer->id, [
    'invoice_settings' => [
        'default_payment_method' => $payment_method,
    ],
]);
redirect('/api/key?updated=1');
