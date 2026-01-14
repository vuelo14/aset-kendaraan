<?php
namespace Controllers; use Core\Controller; use Core\Auth; use Models\Tax; use Models\AuditLog; use Helpers\CSRF;

class TaxController extends Controller {
    public function index(){ Auth::requireLogin(); $this->render('tax/index'); }
    public function store(){ Auth::requireAdmin(); if(!CSRF::check($_POST['csrf'] ?? '')) die('CSRF invalid'); $id=Tax::create($_POST); AuditLog::log('create','tax_payments',$id,$_POST); header('Location: /tax'); }
    public function byVehicle(){ Auth::requireLogin(); $list=Tax::byVehicle($_GET['vehicle_id']); $this->render('tax/byVehicle', compact('list')); }
}
