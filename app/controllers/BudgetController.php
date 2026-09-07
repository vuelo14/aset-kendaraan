<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Budget;
use Models\UsageHistory;
use Helpers\CSRF;

class BudgetController extends Controller
{
    public function index()
    {
        Auth::requireLogin();

        $budget_monitoring = Budget::getBudgetMonitoring();
        $unit_budget_monitoring = Budget::getUnitBudgetMonitoring();

        // Tambahkan penanggung jawab dari riwayat
        foreach ($unit_budget_monitoring as &$u) {
            $u['current_responsible'] = UsageHistory::currentResponsible($u['vehicle_id']);
        }
        unset($u);


        $categories = Budget::allCategories();

        $this->render('budget/index', compact('budget_monitoring', 'unit_budget_monitoring', 'categories'));
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

    public function updateVehicle()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');

        $vehicle_id = $_POST['vehicle_id'] ?? null;
        if (!$vehicle_id) {
            header('Location: /budget');
            exit;
        }

        Budget::updateVehicleBudget($vehicle_id, [
            'budget_limit' => $_POST['budget_limit'] ?? null,
            'budget_category_id' => $_POST['budget_category_id'] ?? null
        ]);

        \Models\AuditLog::log('update_vehicle_budget', 'vehicles', $vehicle_id, [
            'budget_limit' => $_POST['budget_limit'] ?? null,
            'budget_category_id' => $_POST['budget_category_id'] ?? null
        ]);

        $_SESSION['success'] = "Pagu anggaran unit kendaraan berhasil diperbarui!";
        header('Location: /budget');
    }
}

