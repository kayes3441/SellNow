<?php

namespace SellNow\Controllers;

use SellNow\Contracts\UserRepositoryInterface;
use SellNow\Contracts\ProductRepositoryInterface;
use SellNow\Services\PublicService;

class PublicController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepo,
        private ProductRepositoryInterface $productRepo,
        private PublicService $publicService
    )
    {
    }

    public function profile($username): void
    {
        $user = $this->userRepo->findByParams(['username' => $username]);

        if (!$user) {
            $this->renderWithFlash('public/404.html.twig', [
                'message' => 'User not found'
            ]);
            return;
        }

        $products = $this->productRepo->getListWhere(
            orderBy: ['created_at' => 'DESC'],
            filters: ['user_id' => $user['id'], 'is_active' => 1]
        );

        $profileData = $this->publicService->prepareProfileData($user, $products);

        $this->renderWithFlash('public/profile.html.twig', $profileData);
    }
}
