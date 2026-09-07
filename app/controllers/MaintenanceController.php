<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Maintenance;
use Models\AuditLog;
use Models\Komponen;
use Models\MaintenanceDetail;
use Models\Vehicle;
use Models\Budget;
use Helpers\CSRF;
use Helpers\GeminiAI;

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
        if ($maintenance && stripos($maintenance['notes'] ?? '', 'realisasi') !== false) {
            $_SESSION['error'] = "Data pemeliharaan ini berstatus Realisasi dan tidak dapat diedit!";
            $redirect = $_GET['redirect'] ?? '/maintenance';
            header('Location: ' . $redirect);
            exit;
        }
        $vehicles = Vehicle::all();
        $this->render('maintenance/edit', compact('maintenance', 'vehicles'));
    }
    public function update()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');
        $id = $_POST['id'];
        $redirect = !empty($_POST['redirect']) ? $_POST['redirect'] : '/maintenance';
        $m = Maintenance::find($id);
        if ($m && stripos($m['notes'] ?? '', 'realisasi') !== false) {
            $_SESSION['error'] = "Data pemeliharaan tidak dapat diubah karena status sudah Realisasi!";
            header('Location: ' . $redirect);
            exit;
        }
        Maintenance::update($id, $_POST);
        AuditLog::log('update', 'maintenance', $id, $_POST);
        $_SESSION['success'] = "Data pemeliharaan berhasil diperbarui!";
        header('Location: ' . $redirect);
    }
    public function delete()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');
        $id = $_POST['id'];
        $m = Maintenance::find($id);
        if ($m && stripos($m['notes'] ?? '', 'realisasi') !== false) {
            $_SESSION['error'] = "Data pemeliharaan tidak dapat dihapus karena status sudah Realisasi!";
            header('Location: /maintenance');
            exit;
        }
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
        $budget = Budget::getVehicleBudget($vehicle['id'] ?? 0);

        $this->render('maintenance/details', compact('maintenance', 'vehicle', 'details', 'komponen', 'budget'));
    }

    public function storeDetail()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');

        $m = Maintenance::find($_POST['maintenance_id']);
        if ($m && stripos($m['notes'] ?? '', 'realisasi') !== false) {
            $_SESSION['error'] = "Komponen tidak dapat ditambah karena status pemeliharaan sudah Realisasi!";
            header('Location: /maintenance/details?id=' . $_POST['maintenance_id']);
            exit;
        }

        MaintenanceDetail::add($_POST);

        $_SESSION['success'] = "Rincian pemeliharaan berhasil ditambahkan!";
        header('Location: /maintenance/details?id=' . $_POST['maintenance_id']);
    }

    public function updateDetail()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');

        $id = $_POST['id'] ?? null;
        $maintenance_id = $_POST['maintenance_id'] ?? null;
        $harga_satuan = (float)($_POST['harga_satuan'] ?? 0);
        $jumlah = (float)($_POST['jumlah'] ?? 1);

        $m = Maintenance::find($maintenance_id);
        if ($m && stripos($m['notes'] ?? '', 'realisasi') !== false) {
            $_SESSION['error'] = "Harga tidak dapat diubah karena status pemeliharaan sudah Realisasi!";
            header('Location: /maintenance/details?id=' . $maintenance_id);
            exit;
        }

        if ($jumlah <= 0) {
            $jumlah = 1;
        }

        MaintenanceDetail::update($id, [
            'jumlah' => $jumlah,
            'harga_satuan' => $harga_satuan
        ]);

        AuditLog::log('update', 'maintenance_detail', $id, [
            'harga_satuan' => $harga_satuan,
            'jumlah' => $jumlah
        ]);

        $_SESSION['success'] = "Harga rincian pemeliharaan berhasil diperbarui!";
        header('Location: /maintenance/details?id=' . $maintenance_id);
    }

    public function deleteDetail()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');

        $id = $_POST['id'];
        $maintenance_id = $_POST['maintenance_id'];

        $m = Maintenance::find($maintenance_id);
        if ($m && stripos($m['notes'] ?? '', 'realisasi') !== false) {
            $_SESSION['error'] = "Komponen tidak dapat diubah/dihapus karena status pemeliharaan sudah Realisasi!";
            header('Location: /maintenance/details?id=' . $maintenance_id);
            exit;
        }

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

    public function unlock()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');

        $id = $_POST['id'] ?? null;
        $m = Maintenance::find($id);
        if ($m) {
            $notes = trim($m['notes'] ?? '');
            if (stripos($notes, 'realisasi') !== false) {
                $newNotes = preg_replace('/realisasi/i', 'Rencana', $notes);
            } else {
                $newNotes = 'Rencana';
            }
            $data = $m;
            $data['notes'] = trim($newNotes);
            Maintenance::update($id, $data);
            AuditLog::log('unlock', 'maintenance', $id, ['notes' => $newNotes]);
            $_SESSION['success'] = "Kunci status Realisasi berhasil dibuka! Data servis sekarang dapat diedit dan komponen dapat ditambah.";
        }
        $redirect = !empty($_POST['redirect']) ? $_POST['redirect'] : '/maintenance';
        header('Location: ' . $redirect);
    }

    public function lock()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');

        $id = $_POST['id'] ?? null;
        $m = Maintenance::find($id);
        if ($m) {
            $notes = trim($m['notes'] ?? '');
            if (stripos($notes, 'rencana') !== false) {
                $newNotes = preg_replace('/rencana/i', 'Realisasi', $notes);
            } else {
                $newNotes = empty($notes) ? 'Realisasi' : $notes . ' (Realisasi)';
            }
            $data = $m;
            $data['notes'] = trim($newNotes);
            Maintenance::update($id, $data);
            AuditLog::log('lock', 'maintenance', $id, ['notes' => $newNotes]);
            $_SESSION['success'] = "Status pemeliharaan berhasil dikunci ke Realisasi.";
        }
        $redirect = !empty($_POST['redirect']) ? $_POST['redirect'] : '/maintenance';
        header('Location: ' . $redirect);
    }

    public function aiRecommendation()
    {
        Auth::requireLogin();
        header('Content-Type: application/json');

        if (!CSRF::check($_POST['csrf'] ?? '')) {
            echo json_encode([
                'success' => false,
                'error' => 'Token CSRF tidak valid atau sesi kadaluarsa. Silakan refresh halaman.'
            ]);
            exit;
        }

        $id = $_POST['maintenance_id'] ?? null;
        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'ID pemeliharaan tidak ditemukan.']);
            exit;
        }

        $maintenance = Maintenance::find($id);
        if (!$maintenance) {
            echo json_encode(['success' => false, 'error' => 'Data pemeliharaan tidak ditemukan di database.']);
            exit;
        }

        $vehicle = Vehicle::find($maintenance['vehicle_id'] ?? 0);
        if (!$vehicle) {
            echo json_encode(['success' => false, 'error' => 'Data kendaraan tidak ditemukan di database.']);
            exit;
        }

        $currentDetails = MaintenanceDetail::byMaintenance($id);

        // Ambil riwayat servis kendaraan sebelumnya (kecualikan servis saat ini)
        $allHistory = Maintenance::byVehicle($vehicle['id']);
        $historyWithDetails = [];
        foreach ($allHistory as $h) {
            if ($h['id'] == $id) {
                continue;
            }
            $h['details'] = MaintenanceDetail::byMaintenance($h['id']);
            $historyWithDetails[] = $h;
        }

        // Ambil data anggaran unit kendaraan
        $budget = Budget::getVehicleBudget($vehicle['id']);

        // Ambil katalog master komponen
        $catalog = Komponen::all();

        $context = [
            'vehicle' => $vehicle,
            'maintenance' => $maintenance,
            'current_details' => $currentDetails,
            'history' => $historyWithDetails,
            'budget' => $budget,
            'catalog' => $catalog
        ];

        $response = GeminiAI::getRecommendation($context);

        echo json_encode($response);
        exit;
    }

    public function applyAiRecommendations()
    {
        Auth::requireAdmin();
        header('Content-Type: application/json');

        if (!CSRF::check($_POST['csrf'] ?? '')) {
            echo json_encode(['success' => false, 'error' => 'Token CSRF tidak valid atau sesi kadaluarsa.']);
            exit;
        }

        $maintenance_id = $_POST['maintenance_id'] ?? null;
        if (!$maintenance_id) {
            echo json_encode(['success' => false, 'error' => 'ID pemeliharaan tidak valid.']);
            exit;
        }

        $m = Maintenance::find($maintenance_id);
        if (!$m) {
            echo json_encode(['success' => false, 'error' => 'Data pemeliharaan tidak ditemukan.']);
            exit;
        }

        if (stripos($m['notes'] ?? '', 'realisasi') !== false) {
            echo json_encode(['success' => false, 'error' => 'Rincian tidak dapat ditambah karena status pemeliharaan sudah Realisasi (terkunci).']);
            exit;
        }

        $rawItems = $_POST['items'] ?? null;
        $items = [];
        if (is_string($rawItems)) {
            $items = json_decode($rawItems, true) ?: [];
        } elseif (is_array($rawItems)) {
            $items = $rawItems;
        }

        if (empty($items)) {
            echo json_encode(['success' => false, 'error' => 'Tidak ada item rekomendasi yang dipilih untuk ditambahkan.']);
            exit;
        }

        $addedCount = 0;
        $pdo = \Core\Database::getInstance()->pdo();

        foreach ($items as $item) {
            $komponen_id = !empty($item['komponen_id']) ? (int)$item['komponen_id'] : null;
            $nama = trim($item['nama'] ?? '');
            $jenis = in_array(strtolower($item['jenis'] ?? ''), ['jasa', 'komponen']) ? strtolower($item['jenis']) : 'komponen';
            $satuan = trim($item['satuan'] ?? 'Pcs');
            $harga = (float)($item['harga_satuan'] ?? 0);
            $jumlah = (float)($item['jumlah'] ?? 1);

            if ($jumlah <= 0) {
                $jumlah = 1;
            }

            if (!$komponen_id && !empty($nama)) {
                // Cari apakah komponen sudah ada di master
                $stmt = $pdo->prepare("SELECT id FROM komponen WHERE LOWER(nama) = LOWER(?) LIMIT 1");
                $stmt->execute([$nama]);
                $existing = $stmt->fetch();
                if ($existing) {
                    $komponen_id = (int)$existing['id'];
                } else {
                    $komponen_id = Komponen::create([
                        'nama' => $nama,
                        'jenis' => $jenis,
                        'satuan' => $satuan,
                        'harga' => $harga
                    ]);
                }
            }

            if ($komponen_id) {
                MaintenanceDetail::add([
                    'maintenance_id' => $maintenance_id,
                    'komponen_id' => $komponen_id,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga
                ]);
                $addedCount++;
            }
        }

        AuditLog::log('apply_ai_recommendation', 'maintenance_detail', $maintenance_id, ['count' => $addedCount]);

        echo json_encode([
            'success' => true,
            'message' => "Berhasil menambahkan {$addedCount} rincian rekomendasi ke servis ini!",
            'added_count' => $addedCount
        ]);
        exit;
    }
}

