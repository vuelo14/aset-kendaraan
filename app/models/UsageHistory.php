<?php

namespace Models;

use Core\Database;

class UsageHistory
{
    public static function byVehicle($vehicle_id)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('SELECT * FROM usage_history WHERE vehicle_id = ? ORDER BY start_date DESC, id DESC');
        $stmt->execute([$vehicle_id]);
        return $stmt->fetchAll();
    }

    public static function current($vehicle_id)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('SELECT * FROM usage_history WHERE vehicle_id = ? AND (end_date IS NULL OR end_date >= CURDATE()) ORDER BY start_date DESC, id DESC LIMIT 1');
        $stmt->execute([$vehicle_id]);
        return $stmt->fetch();
    }


    public static function create($data)
    {
        $pdo = Database::getInstance()->pdo();

        // Normalisasi tanggal: start_date wajib, end_date boleh kosong → NULL
        $start_date = !empty($data['start_date']) ? $data['start_date'] : null;
        $end_date   = !empty($data['end_date'])   ? $data['end_date']   : null;

        // Validasi sederhana
        if ($start_date === null) {
            throw new \InvalidArgumentException('start_date wajib diisi');
        }

        $sql = "INSERT INTO usage_history (vehicle_id, pemakai, jabatan, start_date, end_date)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        // Saat $end_date = null, PDO akan mengirimkan NULL sehingga MySQL menerima tanpa error
        $stmt->execute([
            $data['vehicle_id'],
            $data['pemakai'],
            $data['jabatan'] ?? null,
            $start_date,
            $end_date    // <= NULL bila kosong
        ]);

        return $pdo->lastInsertId();
    }

    public static function currentResponsible($vehicle_id)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('SELECT pemakai FROM usage_history WHERE vehicle_id=? AND (end_date IS NULL OR end_date >= CURDATE()) ORDER BY start_date DESC LIMIT 1');
        $stmt->execute([$vehicle_id]);
        $row = $stmt->fetch();
        return $row['pemakai'] ?? '';
    }
}
