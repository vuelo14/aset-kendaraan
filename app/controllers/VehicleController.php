<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Vehicle;
use Models\AuditLog;
use Helpers\CSRF;
use Models\UsageHistory;

class VehicleController extends Controller
{
    public function index()
    {

        Auth::requireLogin();

        // Filter untuk SQL (kecuali penanggung)
        $dbFilters = [
            'jenis' => $_GET['jenis'] ?? '',
            'status_kendaraan' => $_GET['status_kendaraan'] ?? '',
            'status_pajak' => $_GET['status_pajak'] ?? '',
            'status_penggunaan' => $_GET['status_penggunaan'] ?? '',
        ];

        $vehicles = Vehicle::all($dbFilters);

        // Tambahkan penanggung jawab dari riwayat
        foreach ($vehicles as &$v) {
            $v['current_responsible'] = UsageHistory::currentResponsible($v['id']);
        }
        unset($v);

        // Filter Penanggung Jawab di PHP (karena datanya dinamis)
        $penanggungSearch = $_GET['penanggung'] ?? '';
        if (!empty($penanggungSearch)) {
            $vehicles = array_filter($vehicles, function ($v) use ($penanggungSearch) {
                // Pencarian case-insensitive
                return stripos($v['current_responsible'] ?? '', $penanggungSearch) !== false;
            });
        }

        // Pass semua filter ke view agar form tetap terisi
        $filters = array_merge($dbFilters, ['penanggung' => $penanggungSearch]);

        $this->render('vehicle/index', compact('vehicles', 'filters'));
    }
    public function create()
    {
        Auth::requireAdmin();
        $this->render('vehicle/create');
    }
    public function store()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');
        $foto_path = null;
        if (!empty($_FILES['foto']['name'])) {
            $file = $_FILES['foto'];

            // 1. Validasi Error
            if ($file['error'] !== UPLOAD_ERR_OK) {
                die("Upload gagal dengan kode error: " . $file['error']);
            }

            // 2. Validasi Tipe File (MIME Type)
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($file['tmp_name']);
            $allowed_mimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

            if (!in_array($mime, $allowed_mimes)) {
                die("Format file tidak diizinkan! Hanya JPG, PNG, atau WEBP.");
            }

            // 3. Validasi Ukuran (Contoh max 2MB)
            if ($file['size'] > 2 * 1024 * 1024) {
                die("Ukuran file terlalu besar! Maksimal 2MB.");
            }

            // Proses Upload
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $name = time() . '_' . uniqid() . '.' . $ext; // Gunakan uniqid agar lebih unik
            $target = UPLOAD_DIR . '/' . $name;

            if (!is_dir(UPLOAD_DIR))
                mkdir(UPLOAD_DIR, 0777, true);

            if (move_uploaded_file($file['tmp_name'], $target)) {
                // Hapus foto lama jika ini proses update
                if (isset($v['foto_path']) && file_exists(__DIR__ . '/../../public' . $v['foto_path'])) {
                    unlink(__DIR__ . '/../../public' . $v['foto_path']);
                }
                $foto_path = '/assets/uploads/vehicles/' . $name;
            }
        }

        $data = [
            'plat' => $_POST['plat'],
            'merk' => $_POST['merk'],
            'tipe' => $_POST['tipe'],
            'tahun' => $_POST['tahun'],
            'jenis' => $_POST['jenis'],
            'kondisi' => $_POST['kondisi'],
            'status_penggunaan' => $_POST['status_penggunaan'],
            'status_kendaraan' => $_POST['status_kendaraan'],
            'foto_path' => $foto_path,
            // Tidak ada current_responsible di sini; biarkan null dulu
            'current_responsible' => null
        ];

        $id = Vehicle::create($data);
        AuditLog::log('create', 'vehicles', $id, $data);
        $_SESSION['success'] = "Data kendaraan berhasil ditambahkan!";
        header('Location: /vehicles');
    }
    public function edit()
    {
        Auth::requireAdmin();
        $v = Vehicle::find($_GET['id']);
        $this->render('vehicle/edit', compact('v'));
    }
    public function update()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');
        $id = $_POST['id'];
        $v = Vehicle::find($id);
        $foto_path = isset($v['foto_path']) ? $v['foto_path'] : null;
        if (!empty($_FILES['foto']['name'])) {
            $file = $_FILES['foto'];

            // 1. Validasi Error
            if ($file['error'] !== UPLOAD_ERR_OK) {
                die("Upload gagal dengan kode error: " . $file['error']);
            }

            // 2. Validasi Tipe File (MIME Type)
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($file['tmp_name']);
            $allowed_mimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

            if (!in_array($mime, $allowed_mimes)) {
                die("Format file tidak diizinkan! Hanya JPG, PNG, atau WEBP.");
            }

            // 3. Validasi Ukuran (Contoh max 2MB)
            if ($file['size'] > 2 * 1024 * 1024) {
                die("Ukuran file terlalu besar! Maksimal 2MB.");
            }

            // Proses Upload
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $name = time() . '_' . uniqid() . '.' . $ext; // Gunakan uniqid agar lebih unik
            $target = UPLOAD_DIR . '/' . $name;

            if (!is_dir(UPLOAD_DIR))
                mkdir(UPLOAD_DIR, 0777, true);

            if (move_uploaded_file($file['tmp_name'], $target)) {
                // Hapus foto lama jika ini proses update
                if (isset($v['foto_path']) && file_exists(__DIR__ . '/../../public' . $v['foto_path'])) {
                    unlink(__DIR__ . '/../../public' . $v['foto_path']);
                }
                $foto_path = '/assets/uploads/vehicles/' . $name;
            }
        }

        $data = [
            'plat' => $_POST['plat'],
            'merk' => $_POST['merk'],
            'tipe' => $_POST['tipe'],
            'tahun' => $_POST['tahun'],
            'jenis' => $_POST['jenis'],
            'kondisi' => $_POST['kondisi'],
            'status_penggunaan' => $_POST['status_penggunaan'],
            'status_kendaraan' => $_POST['status_kendaraan'],
            'foto_path' => $foto_path,
            // Jangan diubah manual; akan diisi otomatis oleh UsageHistory::create()
            'current_responsible' => $v['current_responsible'] // pertahankan nilai lama
        ];

        Vehicle::update($id, $data);
        AuditLog::log('update', 'vehicles', $id, $data);
        $_SESSION['success'] = "Data kendaraan berhasil diperbarui!";
        header('Location: /vehicles');
    }
    public function delete()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');
        $id = $_POST['id'];
        Vehicle::delete($id);
        AuditLog::log('delete', 'vehicles', $id, []);
        $_SESSION['success'] = "Data kendaraan berhasil dihapus!";
        header('Location: /vehicles');
    }

    public function show()
    {
        Auth::requireLogin();
        $id = $_GET['id'] ?? null;
        $v = \Models\Vehicle::find($id);

        // Ambil riwayat & penanggung jawab saat ini
        $history = \Models\UsageHistory::byVehicle($id);
        $current = \Models\UsageHistory::current($id);

        $this->render('vehicle/show', compact('v', 'history', 'current'));
    }
}
