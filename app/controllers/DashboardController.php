<?php
namespace Controllers; use Core\Controller; use Core\Auth; use Core\Database; use Models\Maintenance; use Models\Tax; use Models\Schedule;

class DashboardController extends Controller {
    public function index(){
        Auth::requireLogin();
        $pdo = Database::getInstance()->pdo();
        $counts = [
            'total' => $pdo->query('SELECT COUNT(*) AS c FROM vehicles')->fetch()['c'] ?? 0,
            'roda2' => $pdo->query("SELECT COUNT(*) AS c FROM vehicles WHERE jenis='roda2'")->fetch()['c'] ?? 0,
            'roda4' => $pdo->query("SELECT COUNT(*) AS c FROM vehicles WHERE jenis='roda4'")->fetch()['c'] ?? 0,
            'jabatan' => $pdo->query("SELECT COUNT(*) AS c FROM vehicles WHERE status_penggunaan='jabatan'")->fetch()['c'] ?? 0,
            'operasional' => $pdo->query("SELECT COUNT(*) AS c FROM vehicles WHERE status_penggunaan='operasional'")->fetch()['c'] ?? 0,
        ];
        $upcomingMaint = Schedule::upcomingMaintenance(30);
        $upcomingTax   = Schedule::upcomingTax(30);
        $maintCosts    = Maintenance::monthlyCosts();
        $taxCosts      = Tax::yearlyCosts();
        $this->render('dashboard/index', compact('counts','upcomingMaint','upcomingTax','maintCosts','taxCosts'));
    }
}
