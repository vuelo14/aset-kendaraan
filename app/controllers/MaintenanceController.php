<?php
namespace Controllers; use Core\Controller; use Core\Auth; use Models\Maintenance; use Models\AuditLog; use Helpers\CSRF;

class MaintenanceController extends Controller {
    public function index(){ Auth::requireLogin(); $this->render('maintenance/index'); }
    public function store(){ Auth::requireAdmin(); if(!CSRF::check($_POST['csrf'] ?? '')) die('CSRF invalid'); $id=Maintenance::create($_POST); AuditLog::log('create','maintenance',$id,$_POST); header('Location: /maintenance'); }
    public function byVehicle(){ Auth::requireLogin(); $list=Maintenance::byVehicle($_GET['vehicle_id']); $this->render('maintenance/byVehicle', compact('list')); }
}
