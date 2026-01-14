<?php
namespace Controllers; use Core\Controller; use Core\Auth; use Models\Schedule; use Helpers\CSRF; use Models\AuditLog;

class ScheduleController extends Controller {
    public function maintenance(){ Auth::requireLogin(); $list=Schedule::upcomingMaintenance(365); $this->render('schedule/maintenance', compact('list')); }
    public function storeMaintenance(){ Auth::requireAdmin(); if(!CSRF::check($_POST['csrf'] ?? '')) die('CSRF invalid'); Schedule::createMaintenance($_POST); AuditLog::log('create','maintenance_schedule',null,$_POST); header('Location: /schedule/maintenance'); }
    public function tax(){ Auth::requireLogin(); $list=Schedule::upcomingTax(365); $this->render('schedule/tax', compact('list')); }
    public function storeTax(){ Auth::requireAdmin(); if(!CSRF::check($_POST['csrf'] ?? '')) die('CSRF invalid'); Schedule::createTax($_POST); AuditLog::log('create','tax_schedule',null,$_POST); header('Location: /schedule/tax'); }
}
