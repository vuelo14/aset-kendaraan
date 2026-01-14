<?php

namespace Models;

use Core\Database;

class Tax
{
    public static function byVehicle($vehicle_id)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('SELECT * FROM tax_payments WHERE vehicle_id=? ORDER BY date DESC');
        $stmt->execute([$vehicle_id]);
        return $stmt->fetchAll();
    }
    public static function create($data)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('INSERT INTO tax_payments (vehicle_id, date, jenis, biaya, status) VALUES (?,?,?,?,?)');
        $stmt->execute([$data['vehicle_id'], $data['date'], $data['jenis'], $data['biaya'], $data['status']]);
        return $pdo->lastInsertId();
    }
    public static function yearlyCosts()
    {
        $pdo = Database::getInstance()->pdo();
        return $pdo->query('SELECT YEAR(date) AS y, SUM(biaya) AS total FROM tax_payments GROUP BY y ORDER BY y ASC')->fetchAll();
    }
}
