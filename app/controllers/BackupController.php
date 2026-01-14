<?php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Core\Database;
use Helpers\CSRF;

class BackupController extends Controller
{
    public function index()
    {
        Auth::requireAdmin();
        $this->render('backup/index');
    }
    public function run()
    {
        Auth::requireAdmin();
        $pdo = Database::getInstance()->pdo();
        $tables = [];
        foreach ($pdo->query('SHOW TABLES') as $row) {
            $tables[] = array_values($row)[0];
        }
        $dump = "SET FOREIGN_KEY_CHECKS=0;\n";
        foreach ($tables as $t) {
            $create = $pdo->query('SHOW CREATE TABLE ' . $t)->fetch();
            $dump .= $create['Create Table'] + ";\n\n";
        }
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="backup_' . date('Ymd_His') . '.sql"');
        echo $dump;
        exit;
    }
    public function restore()
    {
        Auth::requireAdmin();
        if (!CSRF::check($_POST['csrf'] ?? '')) die('CSRF invalid');
        if (isset($_FILES['sql']) && is_uploaded_file($_FILES['sql']['tmp_name'])) {
            $sql = file_get_contents($_FILES['sql']['tmp_name']);
            $pdo = Database::getInstance()->pdo();
            $pdo->exec('SET FOREIGN_KEY_CHECKS=0;');
            foreach (preg_split('/;\s*\n/', $sql) as $stmt) {
                if (trim($stmt)) $pdo->exec($stmt);
            }
            $pdo->exec('SET FOREIGN_KEY_CHECKS=1;');
        }
        header('Location: /backup');
    }
}
