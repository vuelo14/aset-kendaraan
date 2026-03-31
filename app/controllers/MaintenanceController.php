<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Maintenance;
use Models\AuditLog;
use Models\Komponen;
use Models\MaintenanceDetail;
use Models\Vehicle;
use Helpers\CSRF;

class MaintenanceController extends Controller
{
    public function index()
    {
        Auth::requireLogin();

        // [BARU] Ambil semua data kendaraan untuk dropdown
        $vehicles = Vehicle::all();

        // [BARU] Tangkap ID dari URL jika ada
        $selected_id = $_GET['vehicle_id'] ?? null;

        // [BARU] Ambil list maintenance
        $maintenances = Maintenance::all();

        // Kirim variabel ke view
        $this->render('maintenance/index', compact('vehicles', 'selected_id', 'maintenances'));
    }
    public function store()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');
        $id = Maintenance::create($_POST);
        AuditLog::log('create', 'maintenance', $id, $_POST);
        $_SESSION['success'] = "Data pemeliharaan berhasil ditambahkan!";
        header('Location: /maintenance');
    }
    public function edit()
    {
        Auth::requireLogin();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /maintenance');
            exit;
        }
        $maintenance = Maintenance::find($id);
        $vehicles = Vehicle::all();
        $this->render('maintenance/edit', compact('maintenance', 'vehicles'));
    }
    public function update()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');
        $id = $_POST['id'];
        Maintenance::update($id, $_POST);
        AuditLog::log('update', 'maintenance', $id, $_POST);
        $_SESSION['success'] = "Data pemeliharaan berhasil diperbarui!";
        header('Location: /maintenance');
    }
    public function delete()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');
        $id = $_POST['id'];
        Maintenance::delete($id);
        AuditLog::log('delete', 'maintenance', $id, []);
        $_SESSION['success'] = "Data pemeliharaan berhasil dihapus!";
        header('Location: /maintenance');
    }
    public function byVehicle()
    {
        Auth::requireLogin();
        $list = Maintenance::byVehicle($_GET['vehicle_id']);
        $this->render('maintenance/byVehicle', compact('list'));
    }

    public function details()
    {
        Auth::requireLogin();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /maintenance');
            exit;
        }

        $maintenance = Maintenance::find($id);
        $vehicle = Vehicle::find($maintenance['vehicle_id'] ?? 0);
        $details = MaintenanceDetail::byMaintenance($id);
        $komponen = Komponen::all();

        $this->render('maintenance/details', compact('maintenance', 'vehicle', 'details', 'komponen'));
    }

    public function storeDetail()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');

        MaintenanceDetail::add($_POST);

        $_SESSION['success'] = "Rincian pemeliharaan berhasil ditambahkan!";
        header('Location: /maintenance/details?id=' . $_POST['maintenance_id']);
    }

    public function deleteDetail()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');

        $id = $_POST['id'];
        $maintenance_id = $_POST['maintenance_id'];

        \Models\MaintenanceDetail::delete($id);

        $_SESSION['success'] = "Rincian pemeliharaan berhasil dihapus!";
        header('Location: /maintenance/details?id=' . $maintenance_id);
    }

    public function nota()
    {
        Auth::requireLogin();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /maintenance');
            exit;
        }

        $maintenance = Maintenance::find($id);
        $vehicle = Vehicle::find($maintenance['vehicle_id'] ?? 0);
        $details = MaintenanceDetail::byMaintenance($id);

        $this->render('maintenance/nota', compact('maintenance', 'vehicle', 'details'));
    }
}
