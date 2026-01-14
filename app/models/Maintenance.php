<?php
namespace Models; use Core\Database;

class Maintenance {
    public static function byVehicle($vehicle_id){ $pdo=Database::getInstance()->pdo(); $stmt=$pdo->prepare('SELECT * FROM maintenance WHERE vehicle_id=? ORDER BY date DESC'); $stmt->execute([$vehicle_id]); return $stmt->fetchAll(); }
    public static function create($data){ $pdo=Database::getInstance()->pdo(); $stmt=$pdo->prepare('INSERT INTO maintenance (vehicle_id, date, jenis, biaya, bengkel, notes) VALUES (?,?,?,?,?,?)'); $stmt->execute([$data['vehicle_id'],$data['date'],$data['jenis'],$data['biaya'],$data['bengkel'],$data['notes']]); return $pdo->lastInsertId(); }
    public static function monthlyCosts(){ $pdo=Database::getInstance()->pdo(); return $pdo->query("SELECT DATE_FORMAT(date,'%Y-%m') AS ym, SUM(biaya) AS total FROM maintenance GROUP BY ym ORDER BY ym ASC")->fetchAll(); }
}
