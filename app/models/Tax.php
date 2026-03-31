<?php

namespace Models;

use Core\Database;

class Tax
{
    public static function all()
    {
        $pdo = Database::getInstance()->pdo();
        // Join, format date, etc.
        $sql = "SELECT t.*, v.plat, v.merk, v.foto_path
                FROM tax_payments t
                LEFT JOIN vehicles v ON t.vehicle_id = v.id
                ORDER BY t.date DESC";
        return $pdo->query($sql)->fetchAll();
    }

    public static function find($id)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('SELECT * FROM tax_payments WHERE id=?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

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

    public static function update($id, $data)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('UPDATE tax_payments SET vehicle_id=?, date=?, jenis=?, biaya=?, status=? WHERE id=?');
        $stmt->execute([$data['vehicle_id'], $data['date'], $data['jenis'], $data['biaya'], $data['status'], $id]);
    }

    public static function delete($id)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('DELETE FROM tax_payments WHERE id=?');
        $stmt->execute([$id]);
    }
    public static function yearlyCosts()
    {
        $pdo = Database::getInstance()->pdo();
        return $pdo->query('SELECT YEAR(date) AS y, SUM(biaya) AS total FROM tax_payments GROUP BY y ORDER BY y ASC')->fetchAll();
    }
}
