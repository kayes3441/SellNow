<?php

namespace SellNow\Services;

class CheckoutService
{

    public function logTransaction(string $transactionId, string $provider, int $userId, float $amount): void
    {
        $logFile = __DIR__ . '/../../storage/logs/transactions.log';
        
        if (!is_dir(dirname($logFile))) {
            mkdir(dirname($logFile), 0755, true);
        }

        $logData = sprintf(
            "[%s] Transaction: %s | Provider: %s | User: %d | Amount: $%.2f\n",
            date('Y-m-d H:i:s'),
            $transactionId,
            $provider,
            $userId,
            $amount
        );

        file_put_contents($logFile, $logData, FILE_APPEND);
    }

    public function validateCheckoutData(array $cart, ?string $provider): array
    {
        $errors = [];

        if (empty($cart)) {
            $errors[] = 'Cart is empty';
        }

        if (empty($provider)) {
            $errors[] = 'Payment provider is required';
        }

        return $errors;
    }
}
