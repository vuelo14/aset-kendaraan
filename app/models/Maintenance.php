<?php
namespace Models;
use Core\Database;

class Maintenance
{
    public static function all()
    {
        $pdo = Database::getInstance()->pdo();
        // Join with vehicles and usage_history (to get the user at the time of maintenance)
        $sql = "SELECT m.*, v.plat, v.merk, v.foto_path, uh.pemakai AS current_responsible 
                FROM maintenance m 
                LEFT JOIN vehicles v ON m.vehicle_id = v.id 
                LEFT JOIN usage_history uh ON uh.vehicle_id = m.vehicle_id AND uh.start_date <= m.date AND (uh.end_date IS NULL OR uh.end_date >= m.date)
                ORDER BY m.date DESC";
        return $pdo->query($sql)->fetchAll();
    }

    public static function find($id)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('
            SELECT m.*, uh.pemakai AS current_responsible 
            FROM maintenance m 
            LEFT JOIN usage_history uh ON uh.vehicle_id = m.vehicle_id AND uh.start_date <= m.date AND (uh.end_date IS NULL OR uh.end_date >= m.date)
            WHERE m.id=?
        ');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function byVehicle($vehicle_id)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('SELECT * FROM maintenance WHERE vehicle_id=? ORDER BY date DESC');
        $stmt->execute([$vehicle_id]);
        return $stmt->fetchAll();
    }

    public static function create($data)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('INSERT INTO maintenance (vehicle_id, date, jenis, biaya, bengkel, notes) VALUES (?,?,?,?,?,?)');
        $stmt->execute([$data['vehicle_id'], $data['date'], $data['jenis'], $data['biaya'], $data['bengkel'], $data['notes']]);
        return $pdo->lastInsertId();
    }

    public static function update($id, $data)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('UPDATE maintenance SET vehicle_id=?, date=?, jenis=?, biaya=?, bengkel=?, notes=? WHERE id=?');
        $stmt->execute([$data['vehicle_id'], $data['date'], $data['jenis'], $data['biaya'], $data['bengkel'], $data['notes'], $id]);
    }

    public static function delete($id)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('DELETE FROM maintenance WHERE id=?');
        $stmt->execute([$id]);
    }

    public static function monthlyCosts()
    {
        $pdo = Database::getInstance()->pdo();
        return $pdo->query("SELECT DATE_FORMAT(date,'%Y-%m') AS ym, SUM(biaya) AS total FROM maintenance GROUP BY ym ORDER BY ym ASC")->fetchAll();
    }
}
