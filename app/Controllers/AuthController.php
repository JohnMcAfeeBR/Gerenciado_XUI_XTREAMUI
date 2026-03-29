<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\UserRepository;
use App\Services\AuthService;

final class AuthController extends Controller
{
    private AuthService $auth;

    public function __construct()
    {
        $this->auth = new AuthService(new UserRepository());
    }

    public function registerForm(): void
    {
        $this->view('auth/register');
    }

    public function register(): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($name === '' || $email === '' || $password === '') {
            $this->view('auth/register', ['error' => 'Preencha todos os campos.']);
            return;
        }

        $this->auth->register($name, $email, $password);
        redirect('/login');
    }

    public function loginForm(): void
    {
        $this->view('auth/login');
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $user = $this->auth->attempt($email, $password, 'client');
        if ($user === null) {
            $this->view('auth/login', ['error' => 'Credenciais inválidas.']);
            return;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        redirect('/client/dashboard');
    }

    public function adminLoginForm(): void
    {
        $this->view('auth/admin_login');
    }

    public function adminLogin(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $user = $this->auth->attempt($email, $password, 'admin');
        if ($user === null) {
            $this->view('auth/admin_login', ['error' => 'Credenciais de administrador inválidas.']);
            return;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        redirect('/admin/dashboard');
    }

    public function logout(): void
    {
        session_destroy();
        redirect('/');
    }
}
