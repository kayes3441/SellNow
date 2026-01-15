<?php

namespace SellNow\Controllers;


class CheckoutController extends Controller
{
    public function index()
    {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            $this->redirect('/cart');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $providers = ['Stripe', 'PayPal', 'Razorpay'];

        $this->render('checkout/index.html.twig', [
            'total' => $total,
            'providers' => $providers
        ]);
    }

    public function process()
    {
        // Redirect to mock payment page instead of finishing
        $provider = $_POST['provider'] ?? 'Unknown';

        // Check cart not empty just in case
        if (empty($_SESSION['cart'])) {
            $this->redirect('/cart');
        }

        // Calculate total again? Or pass it?
        // Let's pass via query string (Insecure! Perfect for assessment)
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $this->redirect("/payment?provider=$provider&total=$total");
    }

    public function payment()
    {
        if (empty($_SESSION['cart'])) {
            $this->redirect('/cart');
        }

        $provider = $_GET['provider'] ?? 'Test';
        $total = $_GET['total'] ?? 0;

        $this->render('checkout/payment.html.twig', [
            'provider' => $provider,
            'total' => $total
        ]);
    }

    public function success()
    {
        $provider = $_POST['provider'] ?? 'Unknown';

        $logFile = __DIR__ . '/../../storage/logs/transactions.log';
        if (!is_dir(dirname($logFile)))
            mkdir(dirname($logFile), 0777, true);

        $data = date('Y-m-d H:i:s') . " - Order processed via $provider - User: " . ($_SESSION['user_id'] ?? 'Guest') . "\n";
        file_put_contents($logFile, $data, FILE_APPEND);

        unset($_SESSION['cart']);

        $this->render('layouts/base.html.twig', [
            'content' => "<h1>Thank you for your purchase!</h1><p>Payment via $provider successful.</p><a href='/dashboard' class='btn btn-primary'>Go to Dashboard</a>"
        ]);
    }
}
