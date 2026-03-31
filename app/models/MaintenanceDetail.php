<?php
namespace Models;
use Core\Database;

class MaintenanceDetail
{
    public static function byMaintenance($maintenance_id)
    {
        $pdo = Database::getInstance()->pdo();
        $sql = "SELECT d.*, k.nama, k.jenis, k.satuan
                FROM maintenance_details d
                JOIN komponen k ON d.komponen_id = k.id
                WHERE d.maintenance_id = ?
                ORDER BY k.jenis, k.nama";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$maintenance_id]);
        return $stmt->fetchAll();
    }

    public static function add($data)
    {
        $pdo = Database::getInstance()->pdo();
        $subtotal = $data['jumlah'] * $data['harga_satuan'];
        $sql = "INSERT INTO maintenance_details (maintenance_id, komponen_id, jumlah, harga_satuan, subtotal)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['maintenance_id'],
            $data['komponen_id'],
            $data['jumlah'],
            $data['harga_satuan'],
            $subtotal
        ]);
        self::updateTotalBiaya($data['maintenance_id']);
    }

    public static function delete($id)
    {
        $pdo = Database::getInstance()->pdo();
        // Get maintenance_id before delete
        $stmt = $pdo->prepare("SELECT maintenance_id FROM maintenance_details WHERE id=?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row) {
            $m_id = $row['maintenance_id'];
            $pdo->prepare("DELETE FROM maintenance_details WHERE id=?")->execute([$id]);
            self::updateTotalBiaya($m_id);
        }
    }

    private static function updateTotalBiaya($maintenance_id)
    {
        $pdo = Database::getInstance()->pdo();
        // Calculate sum
        $stmt = $pdo->prepare("SELECT SUM(subtotal) as total FROM maintenance_details WHERE maintenance_id=?");
        $stmt->execute([$maintenance_id]);
        $result = $stmt->fetch();
        $total = $result['total'] ?: 0;

        // Update maintenance table
        $stmt = $pdo->prepare("UPDATE maintenance SET biaya=? WHERE id=?");
        $stmt->execute([$total, $maintenance_id]);
    }
}
