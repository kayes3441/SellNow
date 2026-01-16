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
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->authRepo->findByParams(params: ['email' => $email]);
        
        if ($user && password_verify($password, $user['password'])) {
            $this->authService->addUserDataInSession(userData: $user);
            $this->redirectWithSuccess(url:'/dashboard', message: 'Welcome back!');
        } else {
            $this->redirectWithError(url:'/login', message: 'Invalid email or password');
        }
    }

    public function registerForm():void
    {
        $this->render('auth/register.html.twig');
    }

    public function register():void
    {
        if (empty($_POST['email']) || empty($_POST['password']))
            die("Fill all fields");

        // Raw SQL
        $sql = "INSERT INTO users (email, username, full_Name, password) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        try {
            $stmt->execute([
                $_POST['email'],
                $_POST['username'],
                $_POST['fullname'],
                $_POST['password']
            ]);
        } catch (\Exception $e) {
            die("Error registering: " . $e->getMessage());
        }

        $this->redirect('/login?msg=Registered successfully');
    }

    public function dashboard()
    {
        if (!isset($_SESSION['user_id']))
            $this->redirect('/login');

        $this->render('dashboard.html.twig', [
            'username' => $_SESSION['username']
        ]);
    }
}
