<?php

namespace SellNow\Controllers;

use SellNow\Contracts\CartRepositoryInterface;
use SellNow\Contracts\OrderRepositoryInterface;
use SellNow\Contracts\PaymentProviderRepositoryInterface;
use SellNow\Services\CartService;
use SellNow\Services\CheckoutService;
use SellNow\Services\OrderService;

class CheckoutController extends Controller
{
    public function __construct(
        private CartRepositoryInterface $cartRepo,
        private OrderRepositoryInterface $orderRepo,
        private PaymentProviderRepositoryInterface $paymentProviderRepo,
        private CheckoutService $checkoutService,
        private CartService $cartService,
        private OrderService $orderService,
    )
    {
    }

    public function index(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirectWithError('/login', 'Please login to checkout');
            return;
        }
        $userId = $_SESSION['user_id'];
        $sessionId = session_id();

        if ($userId) {
            $cart = $this->cartRepo->findByParams(['user_id' =>$userId]);
        } else {
            $cart = $this->cartRepo->findByParams(['session_id' =>$sessionId]);
        }

        if (empty($cart)) {
            $this->redirectWithInfo('/cart', 'Your cart is empty');
            return;
        }

        $total = $this->cartService->getCartTotal($cart);

        $providers = $this->paymentProviderRepo->getListWhere(filters:['is_active' => 1]);

        $this->renderWithFlash('checkout/index.html.twig', [
            'cart' => $cart,
            'total' => $total,
            'providers' => $providers
        ]);
    }

    public function process(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirectWithError('/login', 'Please login to checkout');
            return;
        }

        $userId = $_SESSION['user_id'];
        $sessionId = session_id();
        $data = $this->only(['provider']);

        if ($userId) {
            $cart = $this->cartRepo->findByParams(['user_id' =>$userId]);
        } else {
            $cart = $this->cartRepo->findByParams(['session_id' =>$sessionId]);
        }
        $errors = $this->checkoutService->validateCheckoutData($cart, $data['provider'] ?? null);
        if (!empty($errors)) {
            $this->redirectWithError('/checkout', implode(', ', $errors));
            return;
        }
        $total = $this->cartService->getCartTotal($cart);


        $_SESSION['checkout_data'] = [
            'provider' => $data['provider'],
            'total' => $total,
            'cart' => $cart
        ];

        $this->redirect('/payment');
    }

    public function payment(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirectWithError('/login', 'Please login to continue');
            return;
        }
        if (!isset($_SESSION['checkout_data'])) {
            $this->redirectWithError('/checkout', 'Invalid checkout session');
            return;
        }
        $checkoutData = $_SESSION['checkout_data'];

        $this->renderWithFlash('checkout/payment.html.twig', [
            'provider' => $checkoutData['provider'],
            'total' => $checkoutData['total']
        ]);
    }

    public function success(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirectWithError('/login', 'Please login to continue');
            return;
        }

        if (!isset($_SESSION['checkout_data'])) {
            $this->redirectWithError('/checkout', 'Invalid checkout session');
            return;
        }

        $userId = $_SESSION['user_id'];
        $checkoutData = $_SESSION['checkout_data'];

        $db = \SellNow\Config\Database::getInstance()->getConnection();

        try {
            $db->beginTransaction();

            foreach ($checkoutData['cart'] as $item) {
                $orderData = $this->orderService->getOrderData(
                    $item['product_id'],
                    $userId,
                    $item['price'] * $item['quantity'],
                    $checkoutData['provider']
                );

                $orderId = $this->orderRepo->add($orderData);

                $this->orderRepo->update($orderId, ['payment_status' => 'completed']);

                $this->checkoutService->logTransaction(
                    $orderData['transaction_id'],
                    $checkoutData['provider'],
                    $userId,
                    $orderData['total_amount']
                );
                
                $this->cartRepo->delete($item['id']);
            }

            $db->commit();
            unset($_SESSION['checkout_data']);

            $this->renderWithFlash('checkout/success.html.twig', [
                'provider' => $checkoutData['provider'],
                'total' => $checkoutData['total']
            ]);

        } catch (\Exception $e) {
            $db->rollBack();
            $this->redirectWithError('/checkout', 'Payment processing failed. Please try again.');
        }
    }
}
