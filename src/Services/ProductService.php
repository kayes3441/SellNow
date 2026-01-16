<?php

namespace SellNow\Services;

class ProductService
{
    public function generateSlug(string $title): string
    {
        $slug = strtolower(str_replace(' ', '-', $title));
        $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
        return $slug . '-' . rand(1000, 9999);
    }

    public function handleFileUpload(array $file, string $uploadDir, string $prefix = ''): ?string
    {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // Create upload directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = time() . '_' . $prefix . $file['name'];
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return 'uploads/' . $fileName;
        }

        return null;
    }

    public function validateProductData(array $data): array
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors['title'] = 'Title is required';
        }

        if (empty($data['price']) || $data['price'] <= 0) {
            $errors['price'] = 'Valid price is required';
        }

        return $errors;
    }

    public function getProductData(array $data, int $userId, ?string $imagePath = null, ?string $filePath = null): array
    {
        return [
            'user_id' => $userId,
            'title' => $data['title'],
            'slug' => $this->generateSlug($data['title']),
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'image_path' => $imagePath,
            'file_path' => $filePath,
            'is_active' => 1
        ];
    }

    public function calculatePrice(float $price, float $discount = 0): float
    {
        if ($discount > 0) {
            return $price - ($price * ($discount / 100));
        }
        return $price;
    }
}
