<?php

namespace SellNow\Controllers;

use SellNow\Contracts\ProductRepositoryInterface;
use SellNow\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(
        public ProductRepositoryInterface $productRepo,
        public ProductService $productService,
    )
    {

    }
    public function create(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirectWithError('/login', 'Please login to continue');
            return;
        }
        
        $this->renderWithFlash('products/add.html.twig');
    }

    public function store(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirectWithError('/login', 'Please login to continue');
            return;
        }

        $data = $this->only(['title', 'price', 'description']);

        $errors = $this->productService->validateProductData($data);
        if (!empty($errors)) {
            $errorMessage = implode(', ', $errors);
            $this->redirectWithError('/products/create', $errorMessage);
            return;
        }
        $uploadDir = __DIR__ . '/../../public/uploads/';
        
        $imagePath = null;
        if (isset($_FILES['image'])) {
            $imagePath = $this->productService->handleFileUpload($_FILES['image'], $uploadDir);
        }

        $filePath = null;
        if (isset($_FILES['product_file'])) {
            $filePath = $this->productService->handleFileUpload($_FILES['product_file'], $uploadDir, 'dl_');
        }
        if (!$filePath) {
            $this->redirectWithError('/products/create', 'Product file is required');
            return;
        }

        try {
            $productData = $this->productService->getProductData(
                $data,
                $_SESSION['user_id'],
                $imagePath,
                $filePath
            );

             $this->productRepo->add($productData);

            $this->redirectWithSuccess('/dashboard', 'Product created successfully!');
        } catch (\Exception $e) {
            $this->redirectWithError('/products/create', 'Failed to create product. Please try again.');
        }
    }
}
