<?php

namespace SellNow\Controllers;

use SellNow\Contracts\AuthRepositoryInterface;

class AuthController extends Controller
{


    public function __construct(
        public AuthRepositoryInterface $authRepo,
    )
    {
    }
    public function loginForm():void
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }
        $this->render('auth/login.html.twig');
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Raw SQL, no Model
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && $password == $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $this->redirect('/dashboard');
        } else {
            $this->redirect('/login?error=Invalid credentials');
        }
    }

    public function registerForm()
    {
        $this->render('auth/register.html.twig');
    }

    public function register()
    {
        if (empty($_POST['email']) || empty($_POST['password']))
            die("Fill all fields");

        // Raw SQL
        $sql = "INSERT INTO users (email, username, Full_Name, password) VALUES (?, ?, ?, ?)";
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
