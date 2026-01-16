<?php

namespace SellNow\Services;

class PublicService
{
    public function prepareProfileData(array $user, array $products): array
    {
        return [
            'seller' => $user,
            'products' => $products,
            'product_count' => count($products),
            'seller_name' => $user['full_name'] ?? $user['username']
        ];
    }

    public function formatPrice(float $price): string
    {
        return '$' . number_format($price, 2);
    }

    public function getProductStats(array $products): array
    {
        $totalProducts = count($products);
        $totalValue = array_sum(array_column($products, 'price'));
        
        return [
            'total_products' => $totalProducts,
            'total_value' => $totalValue,
            'average_price' => $totalProducts > 0 ? $totalValue / $totalProducts : 0
        ];
    }
}
