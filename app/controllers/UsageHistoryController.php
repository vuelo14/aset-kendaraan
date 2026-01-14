<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\UsageHistory;
use Helpers\CSRF;

class UsageHistoryController extends Controller
{
    public function index()
    {
        Auth::requireLogin();
        $vehicle_id = $_GET['vehicle_id'] ?? null;
        $list = UsageHistory::byVehicle($vehicle_id);
        $this->render('usage/index', compact('list', 'vehicle_id'));
    }

    public function store()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? '')) die('CSRF invalid');
        UsageHistory::create($_POST);
        header('Location: /usage?vehicle_id=' . $_POST['vehicle_id']);
    }
}
