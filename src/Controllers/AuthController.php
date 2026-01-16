<?php

namespace SellNow\Controllers;

use SellNow\Contracts\AuthRepositoryInterface;
use SellNow\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(
        public AuthRepositoryInterface $authRepo,
        public AuthService $authService
    )
    {
    }
    public function loginForm():void
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }
        $this->renderWithFlash('auth/login.html.twig');
    }

    public function login():void
    {
        $data = $this->only(['email', 'password']);

        $user = $this->authRepo->findByParams(['email' => $data['email']]);
        
        if ($user && password_verify($data['password'], $user['password'])) {
            $this->authService->addUserDataInSession($user);
            $this->redirectWithSuccess('/dashboard', 'Welcome back!');
        } else {
            $this->redirectWithError('/login', 'Invalid email or password');
        }
    }

    public function registerForm():void
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }
        
        $this->renderWithFlash('auth/register.html.twig');
    }

    public function register():void
    {
        $data = $this->only(['email', 'username', 'full_name', 'password']);
        if (empty($data['email']) || empty($data['username']) || empty($data['password'])) {
            $this->redirectWithError('/register', 'Please fill all required fields');
            return;
        }
        $existingUser = $this->authRepo->findByParams(['email' => $data['email']]);
        if ($existingUser) {
            $this->redirectWithError('/register', 'Email already registered');
            return;
        }
        $existingUsername = $this->authRepo->findByParams(['username' => $data['username']]);
        if ($existingUsername) {
            $this->redirectWithError('/register', 'Username already taken');
            return;
        }

        try {
            $this->authRepo->add($this->authService->getAddData($data));
            $this->redirectWithSuccess('/login', 'Registration successful! Please login.');
        } catch (\Exception $e) {
            $this->redirectWithError('/register', 'Registration failed. Please try again.');
        }
    }

    public function dashboard():void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirectWithError('/login', 'Please login to continue');
        }
        $this->render('dashboard.html.twig', [
            'username' => $_SESSION['username']
        ]);
    }
}
