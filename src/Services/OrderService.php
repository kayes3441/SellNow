<?php

namespace SellNow\Services;
 ;

class OrderService
{
    public function getOrderData(int $productId, int $userId, float $totalAmount, string $provider): array
    {
        return [
            'user_id' => $userId,
            'product_id' => $productId,
            'total_amount' => $totalAmount,
            'payment_provider' => $provider,
            'payment_status' => 'pending',
            'transaction_id' => $this->generateTransactionId()
        ];
    }



    public function generateTransactionId(): string
    {
        return 'TXN-' . strtoupper(uniqid()) . '-' . time();
    }

}