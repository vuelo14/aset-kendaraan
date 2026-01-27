<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Helpers\CSRF;
use Models\UsageHistory;
use Models\Vehicle;

class UsageController extends Controller
{
    public function create()
    {
        Auth::requireAdmin();
        $vehicle_id = $_GET['vehicle_id'] ?? null;
        if (!$vehicle_id) {
            http_response_code(400);
            echo "Vehicle ID required";
            return;
        }

        $vehicle = Vehicle::find($vehicle_id);
        if (!$vehicle) {
            http_response_code(404);
            echo "Kendaraan tidak ditemukan";
            return;
        }

        $this->render('usage/create', compact('vehicle'));
    }
    public function edit()
    {
        Auth::requireAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo "Usage ID required";
            return;
        }

        $usage = UsageHistory::find($id);
        if (!$usage) {
            http_response_code(404);
            echo "Penanggung Jawab tidak ditemukan";
            return;
        }

        $vehicle = Vehicle::find($usage['vehicle_id']);
        if (!$vehicle) {
            http_response_code(404);
            echo "Kendaraan tidak ditemukan";
            return;
        }

        $this->render('usage/edit', compact('usage', 'vehicle'));
    }

    public function store()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? '')) die('CSRF invalid');

        $data = [
            'vehicle_id'  => $_POST['vehicle_id'],
            'pemakai'     => $_POST['pemakai'],
            'jabatan'     => $_POST['jabatan'] ?? null,
            'start_date'  => $_POST['start_date'],
            'end_date'    => $_POST['end_date'] ?? null,
        ];
        UsageHistory::create($data);

        // Kembali ke halaman detail kendaraan
        header('Location: /vehicles/show?id=' . $_POST['vehicle_id']);
    }

    public function update()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? '')) die('CSRF invalid');

        $data = [
            'id'          => $_POST['id'],
            'vehicle_id'  => $_POST['vehicle_id'],
            'pemakai'     => $_POST['pemakai'],
            'jabatan'     => $_POST['jabatan'] ?? null,
            'start_date'  => $_POST['start_date'],
            'end_date'    => $_POST['end_date'] ?? null,
        ];

        if (UsageHistory::update($data['id'], $data)) {
            // Set session pesan sukses
            $_SESSION['success'] = "Data riwayat pemakaian berhasil diperbarui!";
        } else {
            $_SESSION['error'] = "Gagal memperbarui data.";
        }

        // Kembali ke halaman detail kendaraan
        header('Location: /vehicles/show?id=' . $_POST['vehicle_id']);
        exit;
    }


    public function delete()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? '')) die('CSRF invalid');

        UsageHistory::delete($_POST['id']);

        // Kembali ke halaman detail kendaraan
        header('Location: /vehicles/show?id=' . $_POST['vehicle_id']);
    }
}
