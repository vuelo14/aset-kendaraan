<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Schedule;
use Models\UsageHistory;
use Helpers\CSRF;
use Models\AuditLog;
use Models\Vehicle;

class ScheduleController extends Controller
{
    public function maintenance()
    {
        Auth::requireLogin();
        $vehicles = Vehicle::all();
        $selected_id = $_GET['vehicle_id'] ?? null;
        $list = Schedule::allMaintenance();
        $this->render('schedule/maintenance', compact('list', 'vehicles', 'selected_id'));
    }
    public function storeMaintenance()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');
        Schedule::createMaintenance($_POST);
        AuditLog::log('create', 'maintenance_schedule', null, $_POST);
        $_SESSION['success'] = "Jadwal pemeliharaan berhasil ditambahkan!";
        header('Location: /schedule/maintenance');
    }
    public function editMaintenance()
    {
        Auth::requireLogin();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /schedule/maintenance');
            exit;
        }
        $schedule = Schedule::findMaintenance($id);
        $vehicles = Vehicle::all();
        $this->render('schedule/edit_maintenance', compact('schedule', 'vehicles'));
    }
    public function updateMaintenance()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');
        $id = $_POST['id'];
        Schedule::updateMaintenance($id, $_POST);
        $_SESSION['success'] = "Jadwal pemeliharaan berhasil diperbarui!";
        header('Location: /schedule/maintenance');
    }
    public function deleteMaintenance()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');
        $id = $_POST['id'];
        Schedule::deleteMaintenance($id);
        $_SESSION['success'] = "Jadwal pemeliharaan berhasil dihapus!";
        header('Location: /schedule/maintenance');
    }

    public function tax()
    {
        Auth::requireLogin();
        $vehicles = Vehicle::all();
        $selected_id = $_GET['vehicle_id'] ?? null;
        $list = Schedule::allTax();
        foreach ($list as &$l) {
            $l['current_responsible'] = UsageHistory::currentResponsible($l['vehicle_id']);
        }
        unset($l);
        $this->render('schedule/tax', compact('list', 'vehicles', 'selected_id'));
    }
    public function storeTax()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');
        Schedule::createTax($_POST);
        AuditLog::log('create', 'tax_schedule', null, $_POST);
        $_SESSION['success'] = "Jadwal pajak berhasil ditambahkan!";
        header('Location: /schedule/tax');
    }
    public function editTax()
    {
        Auth::requireLogin();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /schedule/tax');
            exit;
        }
        $schedule = Schedule::findTax($id);
        $vehicles = Vehicle::all();
        $this->render('schedule/edit_tax', compact('schedule', 'vehicles'));
    }
    public function updateTax()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');
        $id = $_POST['id'];
        Schedule::updateTax($id, $_POST);
        $_SESSION['success'] = "Jadwal pajak berhasil diperbarui!";
        header('Location: /schedule/tax');
    }
    public function deleteTax()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');
        $id = $_POST['id'];
        Schedule::deleteTax($id);
        $_SESSION['success'] = "Jadwal pajak berhasil dihapus!";
        header('Location: /schedule/tax');
    }
    public function completeTax()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? ''))
            die('CSRF invalid');

        $id = $_POST['id'];
        $paid_date = $_POST['paid_date'];
        $cost = $_POST['cost'];

        // 1. Get Schedule details
        $schedule = Schedule::findTax($id);

        if ($schedule) {
            // 2. Create Tax Record
            // Map tax_type to readable string or use as is
            $jenis = ($schedule['tax_type'] === '5_tahunan') ? '5_tahunan' : 'tahunan';

            \Models\Tax::create([
                'vehicle_id' => $schedule['vehicle_id'],
                'date' => $paid_date,
                'jenis' => $jenis,
                'biaya' => $cost,
                'status' => 'sudah'
            ]);

            // 3. Update Next Date (auto-advance)
            Schedule::updateTaxNextDate($id);

            $_SESSION['success'] = "Pajak berhasil dibayar dan jadwal diperbarui!";
        }

        header('Location: /schedule/tax');
    }

    public function exportTaxPdf()
    {
        Auth::requireLogin();

        $list = Schedule::allTax();

        // Buat HTML untuk PDF
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: sans-serif; }
                h2 { text-align: center; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #333; padding: 6px; font-size: 12px; }
                th { background-color: #e0e0e0ff; }
                tbody tr:nth-child(even) { background-color: #f4f0ddff; }
                .text-center { text-align: center; }
                .text-right { text-align: right; }
            </style>
            <title>REKAPITULASI JADWAL PEMBAYARAN PAJAK KENDARAAN DINAS</title>
        </head>
        <body>
            <h2>REKAPITULASI JADWAL PEMBAYARAN PAJAK KENDARAAN DINAS</h2>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">Nomor</th>
                        <th style="width: 15%">Kendaraan</th>
                        <th style="width: 15%">Jenis Kendaraan</th>
                        <th style="width: 20%">Penanggung Jawab</th>
                        <th style="width: 10%">Jenis Pajak</th>
                        <th style="width: 15%">Deskripsi</th>
                        <th style="width: 15%">Perkiraan Biaya</th>
                        <th style="width: 10%">Tanggal</th>
                    </tr>
                </thead>
                <tbody>';

        $no = 1;
        foreach ($list as $r) {
            $jenis_pajak = (isset($r['tax_type']) && $r['tax_type'] === '5_tahunan') ? '5 Tahunan' : 'Tahunan';
            $biaya = 'Rp ' . number_format($r['estimated_cost'] ?? 0, 0, ',', '.');

            // Fetch current responsible dynamically from UsageHistory
            $pj = \Models\UsageHistory::currentResponsible($r['vehicle_id']);
            if (empty($pj))
                $pj = '-';
            else
                $pj = htmlspecialchars($pj);

            if ($r['jenis_kendaraan'] === 'roda4') {
                $jenis_kendaraan = 'Roda 4';
            } else if ($r['jenis_kendaraan'] === 'roda2') {
                $jenis_kendaraan = 'Roda 2';
            } else {
                $jenis_kendaraan = 'Lainnya';
            }

            $html .= '<tr>
                <td class="text-center">' . $no++ . '</td>
                <td>' . htmlspecialchars($r['plat']) . '<br><small>' . htmlspecialchars($r['merk']) . '</small></td>
                <td>' . $jenis_kendaraan . '</td>
                <td>' . $pj . '</td>
                <td class="text-center">' . $jenis_pajak . '</td>
                <td>' . htmlspecialchars($r['description']) . '</td>
                <td class="text-right">' . $biaya . '</td>
                <td class="text-center">' . date('d-m-Y', strtotime($r['next_date'])) . '</td>
            </tr>';
        }

        $html .= '</tbody>
            </table>
            <div style="margin-top: 20px; text-align: right;">
                <p>Dicetak pada: ' . date('d F Y H:i') . '</p>
            </div>
        </body>
        </html>';

        // Generate PDF
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->setOptions(new \Dompdf\Options(['isRemoteEnabled' => true]));
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); // Landscape agar kolom muat
        $dompdf->render();
        $dompdf->stream("rekap_jadwal_pajak_" . date('Ymd') . ".pdf", ["Attachment" => false]);
    }
}