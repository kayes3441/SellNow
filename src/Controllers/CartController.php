<?php

namespace SellNow\Controllers;

use SellNow\Contracts\CartRepositoryInterface;
use SellNow\Contracts\ProductRepositoryInterface;
use SellNow\Services\CartService;

class CartController extends Controller
{
    public function __construct(
        private CartRepositoryInterface $cartRepo,
        private ProductRepositoryInterface $productRepo,
        private CartService $cartService
    )
    {
    }

    public function index(): void
    {
        $userId = $_SESSION['user_id'] ?? null;
        $sessionId = session_id();

        if ($userId) {
            $cart = $this->cartRepo->getListWhere(['user_id' =>$userId]);
        } else {
            $cart = $this->cartRepo->getListWhere(['session_id' =>$sessionId]);
        }

        $total = $this->cartService->getCartTotal($cart);

        $this->renderWithFlash('cart/index.html.twig', [
            'cart' => $cart,
            'total' => $total
        ]);
    }

    public function add(): void
    {
        $data = $this->only(['product_id', 'quantity']);

        if (empty($data['product_id'])) {
            $this->jsonError('Product ID is required', [], 400);
            return;
        }

        $productId = (int) $data['product_id'];
        $quantity = (int) ($data['quantity'] ?? 1);
        $userId = $_SESSION['user_id'] ?? null;
        $sessionId = session_id();

         $product = $this->productRepo->findById($productId);
        if (!$product) {
            $this->jsonError('Product not found', [], 404);
            return;
        }

        if (!$product['is_active']) {
            $this->jsonError('Product is not available', [], 400);
            return;
        }

        $existingItem = $this->cartRepo->getListWhere(filters:['product_id' =>$productId, 'user_id' =>$userId]);

        if ($existingItem) {
            $newQuantity = $existingItem['quantity'] + $quantity;
            $this->cartRepo->update(id:$existingItem['id'],data: ['quantity' => $newQuantity]);
            $message = 'Cart updated';
            $action = 'updated';
        } else {
            $cartData = $this->cartService->getCartData($productId, $quantity, $userId, $sessionId);
            $this->cartRepo->add($cartData);
            $message = 'Added to cart';
            $action = 'added';
        }

        if ($userId) {
            $cart = $this->cartRepo->getListWhere(['user_id' =>$userId]);
        } else {
            $cart = $this->cartRepo->getListWhere(['session_id' =>$sessionId]);
        }
        $count = $this->cartService->getCartCount($cart);

        $this->jsonSuccess($message, [
            'count' => $count,
            'action' => $action
        ]);
    }

    public function clear(): void
    {
        $userId = $_SESSION['user_id'] ?? null;
        $sessionId = session_id();
        if ($userId) {
            $cart = $this->cartRepo->getListWhere(['user_id' =>$userId]);
        } else {
            $cart = $this->cartRepo->getListWhere(['session_id' =>$sessionId]);
        }
        $this->cartRepo->delete($cart['id']);
        $this->redirectWithSuccess('/cart', 'Cart cleared successfully');
    }
}
