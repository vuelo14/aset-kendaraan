<?php
namespace Controllers; use Core\Controller; use Core\Auth; use Models\User; use Helpers\CSRF;

class UserController extends Controller {
    public function index(){ Auth::requireAdmin(); $users=User::all(); $this->render('user/index', compact('users')); }
    public function store(){ Auth::requireAdmin(); if(!CSRF::check($_POST['csrf'] ?? '')) die('CSRF invalid'); User::create($_POST); header('Location: /users'); }
}
