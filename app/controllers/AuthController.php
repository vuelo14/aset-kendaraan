<?php

namespace Controllers;

use Core\Controller;
use Models\User;
use Helpers\CSRF;

class AuthController extends Controller
{
    public function login()
    {
        if (isset($_SESSION['user'])) {
            header('Location: /');
            return;
        }
        $this->render('auth/login');
    }
    public function doLogin()
    {
        if (!CSRF::check($_POST['csrf'] ?? '')) die('CSRF invalid');
        $u = User::findByUsername($_POST['username']);

        // var_dump($u);
        // echo password_hash('admin123', PASSWORD_DEFAULT);
        // exit;

        if ($u && password_verify($_POST['password'], $u['password'])) {
            $_SESSION['user'] = ['id' => $u['id'], 'name' => $u['name'], 'username' => $u['username'], 'role' => $u['role']];
            header('Location: /');
        } else {
            $_SESSION['error'] = 'Login gagal';
            header('Location: /login');
        }
    }
    public function logout()
    {
        unset($_SESSION['user']);
        header('Location: /login');
    }
}
