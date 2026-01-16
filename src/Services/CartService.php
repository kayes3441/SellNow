<?php

namespace SellNow\Services;

class CartService
{
    public function getCartTotal(array $cartItems): float
    {
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public function getCartCount(array $cartItems): int
    {
        return count($cartItems);
    }

    public function getCartData(int $productId, int $quantity, ?int $userId = null, ?string $sessionId = null): array
    {
        return [
            'product_id' => $productId,
            'quantity' => $quantity,
            'user_id' => $userId,
            'session_id' => $sessionId
        ];
    }
}
