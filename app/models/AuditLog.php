<?php
namespace Models; use Core\Database; use Core\Auth;

class AuditLog {
    public static function log($action,$table,$record_id,$changes){
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare("INSERT INTO audit_logs (user_id, action, table_name, record_id, changes, created_at) VALUES (?,?,?,?,?,NOW())");
        $user_id = Auth::user()['id'] ?? null;
        $stmt->execute([$user_id,$action,$table,$record_id,json_encode($changes, JSON_UNESCAPED_UNICODE)]);
    }
}
