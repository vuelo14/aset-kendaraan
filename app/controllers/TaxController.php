<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Tax;
use Models\AuditLog;
use Helpers\CSRF;
use Models\Vehicle;

class TaxController extends Controller
{
    public function index()
    {
        Auth::requireLogin();
        $vehicles = Vehicle::all();
        $selected_id = $_GET['vehicle_id'] ?? null;
        $taxList = Tax::all();
        $this->render('tax/index', compact('vehicles', 'selected_id', 'taxList'));
    }

    public function store()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? '')) die('CSRF invalid');
        $id = Tax::create($_POST);
        AuditLog::log('create', 'tax_payments', $id, $_POST);
        $_SESSION['success'] = "Data pembayaran pajak berhasil ditambahkan!";
        header('Location: /tax');
    }

    public function edit()
    {
        Auth::requireLogin();
        $id = $_GET['id'] ?? null;
        if (!$id) { header('Location: /tax'); exit; }
        $tax = Tax::find($id);
        $vehicles = Vehicle::all();
        $this->render('tax/edit', compact('tax', 'vehicles'));
    }

    public function update()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? '')) die('CSRF invalid');
        $id = $_POST['id'];
        Tax::update($id, $_POST);
        AuditLog::log('update', 'tax_payments', $id, $_POST);
        $_SESSION['success'] = "Data pembayaran pajak berhasil diperbarui!";
        header('Location: /tax');
    }

    public function delete()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? '')) die('CSRF invalid');
        $id = $_POST['id'];
        Tax::delete($id);
        AuditLog::log('delete', 'tax_payments', $id, []);
        $_SESSION['success'] = "Data pembayaran pajak berhasil dihapus!";
        header('Location: /tax');
    }

    public function byVehicle()
    {
        Auth::requireLogin();
        $list = Tax::byVehicle($_GET['vehicle_id']);
        $this->render('tax/byVehicle', compact('list'));
    }
}
