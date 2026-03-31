<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\User;
use Models\AuditLog;
use Helpers\CSRF;

class UserController extends Controller
{
    public function index()
    {
        Auth::requireAdmin();
        $users = User::all();
        $this->render('user/index', compact('users'));
    }
    public function store()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? '')) die('CSRF invalid');

        try {
            // Cek apakah username sudah ada (Opsional, tapi validasi manual lebih baik)
            if (User::findByUsername($_POST['username'])) {
                throw new \Exception("Username sudah digunakan, silakan pilih yang lain.");
            }

            $id = User::create($_POST);
            AuditLog::log('create', 'users', $id, $_POST);

            // Set Pesan Sukses
            $_SESSION['success'] = "Pengguna berhasil ditambahkan!";
        } catch (\Exception $e) {
            // Set Pesan Error (misal: duplikat username)
            $_SESSION['error'] = "Gagal menambah pengguna: " . $e->getMessage();
        }

        header('Location: /users');
        exit;
    }
    public function edit()
    {
        Auth::requireAdmin();
        $id = $_GET['id'] ?? null;
        $user = User::find($id);

        if (!$user) {
            header('Location: /users');
            exit;
        }

        $this->render('user/edit', compact('user'));
    }
    public function update()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? '')) die('CSRF invalid');

        try {
            $id = $_POST['id'];
            $data = [
                'name' => $_POST['name'],
                'username' => $_POST['username'],
                'role' => $_POST['role'],
                'password' => $_POST['password']
            ];

            // Cek username unik (kecuali milik sendiri)
            $existing = User::findByUsername($data['username']);
            if ($existing && $existing['id'] != $id) {
                throw new \Exception("Username sudah digunakan oleh pengguna lain.");
            }

            User::update($id, $data);

            // Log (hapus password dari log)
            $logData = $data;
            unset($logData['password']);
            AuditLog::log('update', 'users', $id, $logData);

            // Set Pesan Sukses
            $_SESSION['success'] = "Data pengguna berhasil diperbarui!";
        } catch (\Exception $e) {
            // Set Pesan Error
            $_SESSION['error'] = "Gagal update: " . $e->getMessage();
        }

        header('Location: /users');
        exit;
    }
    public function delete()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? '')) die('CSRF invalid');

        try {
            $id = $_POST['id'];

            // Validasi: Jangan hapus diri sendiri
            if ($id == $_SESSION['user']['id']) {
                throw new \Exception("Anda tidak dapat menghapus akun sendiri saat sedang login.");
            }

            User::delete($id);
            AuditLog::log('delete', 'users', $id, []);

            // Set Pesan Sukses
            $_SESSION['success'] = "Pengguna berhasil dihapus!";
        } catch (\Exception $e) {
            // Set Pesan Error
            $_SESSION['error'] = "Gagal menghapus: " . $e->getMessage();
        }

        header('Location: /users');
        exit;
    }
}
