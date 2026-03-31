<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Komponen;
use Helpers\CSRF;

class KomponenController extends Controller
{
    public function index()
    {
        Auth::requireLogin();
        $komponen = Komponen::all();
        $this->render('komponen/index', compact('komponen'));
    }

    public function store()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');

        Komponen::create($_POST);

        $_SESSION['success'] = "Data komponen/jasa berhasil ditambahkan!";
        header('Location: /komponen');
    }

    public function edit()
    {
        Auth::requireAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /komponen');
            exit;
        }

        $k = Komponen::find($id);
        $this->render('komponen/edit', compact('k'));
    }

    public function update()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');

        $id = $_POST['id'];
        Komponen::update($id, $_POST);

        $_SESSION['success'] = "Data komponen/jasa berhasil diperbarui!";
        header('Location: /komponen');
    }

    public function delete()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');

        $id = $_POST['id'];
        Komponen::delete($id);

        $_SESSION['success'] = "Data komponen/jasa berhasil dihapus!";
        header('Location: /komponen');
    }
}
