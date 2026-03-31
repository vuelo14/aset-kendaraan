<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Budget;
use Helpers\CSRF;

class BudgetController extends Controller
{
    public function index()
    {
        Auth::requireLogin();

        $budget_monitoring = Budget::getBudgetMonitoring();
        $unit_budget_monitoring = Budget::getUnitBudgetMonitoring();

        $this->render('budget/index', compact('budget_monitoring', 'unit_budget_monitoring'));
    }

    public function edit()
    {
        Auth::requireAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /budget');
            exit;
        }

        $category = Budget::findCategory($id);
        $this->render('budget/edit', compact('category'));
    }

    public function update()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');

        $id = $_POST['id'];
        Budget::updateCategory($id, $_POST);

        $_SESSION['success'] = "Pengaturan anggaran berhasil diperbarui!";
        header('Location: /budget');
    }
}
