<?php

namespace Models;

use Core\Database;

class Usage
{
    public static function create($data)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('INSERT INTO usage_history (vehicle_id, pemakai, jabatan, start_date, end_date) VALUES (?,?,?,?,?)');
        $stmt->execute([$data['vehicle_id'], $data['pemakai'], $data['jabatan'], $data['start_date'], $data['end_date']]);
    }
    public static function byVehicle($vehicle_id)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('SELECT * FROM usage_history WHERE vehicle_id=? ORDER BY start_date DESC');
        $stmt->execute([$vehicle_id]);
        return $stmt->fetchAll();
    }
}
