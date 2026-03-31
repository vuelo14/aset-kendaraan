<?php

namespace Models;

use Core\Database;

class PlateHistory
{
    public static function all()
    {
        $pdo = Database::getInstance()->pdo();
        $sql = 'SELECT p.*, v.merk, v.tipe, v.plat AS current_plat 
                FROM plate_histories p 
                JOIN vehicles v ON p.vehicle_id = v.id 
                ORDER BY p.tanggal_ganti DESC, p.id DESC';
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public static function find($id)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('SELECT p.*, v.merk, v.tipe, v.plat AS current_plat FROM plate_histories p JOIN vehicles v ON p.vehicle_id = v.id WHERE p.id=?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data)
    {
        $pdo = Database::getInstance()->pdo();
        
        $sql = "INSERT INTO plate_histories (vehicle_id, plat_lama, plat_baru, tanggal_ganti, keterangan)
                VALUES (?, ?, ?, ?, ?)";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['vehicle_id'],
            $data['plat_lama'],
            $data['plat_baru'],
            $data['tanggal_ganti'],
            $data['keterangan'] ?? null
        ]);
        
        // Update the vehicle's plate? Usually if we log a new plate history, we might want to update the actual vehicle.
        // I will do that in the Controller.
        
        return $pdo->lastInsertId();
    }

    public static function update($id, $data)
    {
        $pdo = Database::getInstance()->pdo();
        
        $sql = "UPDATE plate_histories
                SET vehicle_id = ?, plat_lama = ?, plat_baru = ?, tanggal_ganti = ?, keterangan = ?
                WHERE id = ?";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['vehicle_id'],
            $data['plat_lama'],
            $data['plat_baru'],
            $data['tanggal_ganti'],
            $data['keterangan'] ?? null,
            $id
        ]);
    }

    public static function delete($id)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('DELETE FROM plate_histories WHERE id=?');
        $stmt->execute([$id]);
    }
}
