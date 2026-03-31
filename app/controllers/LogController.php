<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Core\Database;

class LogController extends Controller
{
    public function index()
    {
        Auth::requireAdmin();
        $pdo = Database::getInstance()->pdo();

        // Join ke tabel users untuk mendapatkan nama pelaku
        $sql = "SELECT a.*, u.name as user_name, u.username 
                FROM audit_logs a 
                LEFT JOIN users u ON a.user_id = u.id 
                ORDER BY a.created_at DESC LIMIT 100";

        $logs = $pdo->query($sql)->fetchAll();
        $this->render('log/index', compact('logs'));
    }
}
