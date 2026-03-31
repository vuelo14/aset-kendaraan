<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\PlateHistory;
use Models\Vehicle;
use Models\AuditLog;
use Helpers\CSRF;

class PlateHistoryController extends Controller
{
    public function index()
    {
        Auth::requireLogin();
        $histories = PlateHistory::all();
        $this->render('plate_history/index', compact('histories'));
    }

    public function create()
    {
        Auth::requireAdmin();
        $vehicles = Vehicle::all(); // Assuming this returns all vehicles efficiently
        $this->render('plate_history/create', compact('vehicles'));
    }

    public function store()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? '')) {
            die('CSRF invalid');
        }

        $data = [
            'vehicle_id' => $_POST['vehicle_id'],
            'plat_lama' => $_POST['plat_lama'],
            'plat_baru' => $_POST['plat_baru'],
            'tanggal_ganti' => $_POST['tanggal_ganti'],
            'keterangan' => $_POST['keterangan'] ?? ''
        ];

        $id = PlateHistory::create($data);

        // Optionally update the vehicle itself with the new plate
        if (isset($_POST['update_vehicle']) && $_POST['update_vehicle'] == '1') {
            $vehicle = Vehicle::find($_POST['vehicle_id']);
            if ($vehicle) {
                // We just update the 'plat' column. We can use Vehicle::update but it requires all columns.
                // Best is to fetch all, change plat.
                $vData = [
                    'plat' => $data['plat_baru'],
                    'merk' => $vehicle['merk'],
                    'tipe' => $vehicle['tipe'],
                    'tahun' => $vehicle['tahun'],
                    'jenis' => $vehicle['jenis'],
                    'kondisi' => $vehicle['kondisi'],
                    'status_penggunaan' => $vehicle['status_penggunaan'],
                    'status_kendaraan' => $vehicle['status_kendaraan'],
                    'foto_path' => $vehicle['foto_path'],
                    'current_responsible' => $vehicle['current_responsible']
                ];
                Vehicle::update($vehicle['id'], $vData);
                AuditLog::log('update', 'vehicles', $vehicle['id'], $vData);
            }
        }

        AuditLog::log('create', 'plate_histories', $id, $data);
        $_SESSION['success'] = "Riwayat ganti plat berhasil ditambahkan!";
        header('Location: /plate-history');
    }

    public function edit()
    {
        Auth::requireAdmin();
        $history = PlateHistory::find($_GET['id']);
        if (!$history) {
            die('Data not found');
        }
        $vehicles = Vehicle::all();
        $this->render('plate_history/edit', compact('history', 'vehicles'));
    }

    public function update()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? '')) {
            die('CSRF invalid');
        }

        $id = $_POST['id'];
        $data = [
            'vehicle_id' => $_POST['vehicle_id'],
            'plat_lama' => $_POST['plat_lama'],
            'plat_baru' => $_POST['plat_baru'],
            'tanggal_ganti' => $_POST['tanggal_ganti'],
            'keterangan' => $_POST['keterangan'] ?? ''
        ];

        PlateHistory::update($id, $data);
        AuditLog::log('update', 'plate_histories', $id, $data);

        // Optionally update vehicle plate as well
        if (isset($_POST['update_vehicle']) && $_POST['update_vehicle'] == '1') {
            $vehicle = Vehicle::find($_POST['vehicle_id']);
            if ($vehicle) {
                $vData = [
                    'plat' => $data['plat_baru'],
                    'merk' => $vehicle['merk'],
                    'tipe' => $vehicle['tipe'],
                    'tahun' => $vehicle['tahun'],
                    'jenis' => $vehicle['jenis'],
                    'kondisi' => $vehicle['kondisi'],
                    'status_penggunaan' => $vehicle['status_penggunaan'],
                    'status_kendaraan' => $vehicle['status_kendaraan'],
                    'foto_path' => $vehicle['foto_path'],
                    'current_responsible' => $vehicle['current_responsible']
                ];
                Vehicle::update($vehicle['id'], $vData);
            }
        }

        $_SESSION['success'] = "Riwayat ganti plat berhasil diperbarui!";
        header('Location: /plate-history');
    }

    public function delete()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? '')) {
            die('CSRF invalid');
        }

        $id = $_POST['id'];
        PlateHistory::delete($id);
        AuditLog::log('delete', 'plate_histories', $id, []);
        
        $_SESSION['success'] = "Riwayat ganti plat berhasil dihapus!";
        header('Location: /plate-history');
    }
}
