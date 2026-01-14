<?php
namespace Controllers; use Core\Controller; use Core\Auth; use Models\Vehicle; use Helpers\CSRF;

class ImportExportController extends Controller {
    public function index(){ Auth::requireAdmin(); $this->render('import/index'); }
    public function importCSV(){
        Auth::requireAdmin(); if(!CSRF::check($_POST['csrf'] ?? '')) die('CSRF invalid');
        if(isset($_FILES['csv']) && is_uploaded_file($_FILES['csv']['tmp_name'])){
            $rows = array_map('str_getcsv', file($_FILES['csv']['tmp_name']));
            $header = array_map('trim', array_shift($rows));
            foreach($rows as $row){
                $data = array_combine($header, $row);
                $data['foto_path'] = null;
                $data['current_responsible'] = $data['current_responsible'] ?? '';
                Vehicle::create($data);
            }
        }
        header('Location: /vehicles');
    }
    public function exportVehiclesCSV(){
        Auth::requireLogin(); $list = Vehicle::all();
        header('Content-Type: text/csv'); header('Content-Disposition: attachment; filename="vehicles.csv"');
        $out = fopen('php://output','w');
        fputcsv($out, ['id','plat','merk','tipe','tahun','jenis','status_penggunaan','status_kendaraan','pajak_status','current_responsible']);
        foreach($list as $r){ fputcsv($out, [$r['id'],$r['plat'],$r['merk'],$r['tipe'],$r['tahun'],$r['jenis'],$r['status_penggunaan'],$r['status_kendaraan'],$r['pajak_status'],$r['current_responsible']]); }
        fclose($out);
    }
    public function exportVehiclesPDF(){
        Auth::requireLogin(); $list = Vehicle::all();
        if(class_exists('Dompdf\\Dompdf')){
            $html = '<h2>Daftar Kendaraan</h2><table border="1" cellpadding="6" cellspacing="0"><tr><th>Plat</th><th>Merk</th><th>Tipe</th><th>Tahun</th><th>Jenis</th><th>Status</th></tr>';
            foreach($list as $r){ $html .= '<tr><td>'.$r['plat'].'</td><td>'.$r['merk'].'</td><td>'.$r['tipe'].'</td><td>'.$r['tahun'].'</td><td>'.$r['jenis'].'</td><td>'.$r['status_kendaraan'].'</td></tr>'; }
            $html .= '</table>';
            $dompdf = new \Dompdf\Dompdf(); $dompdf->loadHtml($html); $dompdf->setPaper('A4', 'portrait'); $dompdf->render(); $dompdf->stream('vehicles.pdf');
        } else {
            header('Content-Type: text/html'); echo '<p>Library dompdf belum terpasang. Jalankan: <code>composer require dompdf/dompdf</code></p>';
        }
    }
}
