<?php
namespace Models;
use Core\Database;

class Schedule
{
    // === Maintenance Schedule ===
    public static function allMaintenance()
    {
        $pdo = Database::getInstance()->pdo();
        $sql = "SELECT s.*, v.plat, v.merk, v.foto_path FROM maintenance_schedule s JOIN vehicles v ON v.id=s.vehicle_id ORDER BY s.next_date ASC";
        return $pdo->query($sql)->fetchAll();
    }
    public static function findMaintenance($id)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare("SELECT * FROM maintenance_schedule WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public static function createMaintenance($data)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('INSERT INTO maintenance_schedule (vehicle_id, description, interval_days, next_date) VALUES (?,?,?,?)');
        $stmt->execute([$data['vehicle_id'], $data['description'], $data['interval_days'], $data['next_date']]);
        return $pdo->lastInsertId();
    }
    public static function updateMaintenance($id, $data)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare("UPDATE maintenance_schedule SET vehicle_id=?, description=?, interval_days=?, next_date=? WHERE id=?");
        $stmt->execute([$data['vehicle_id'], $data['description'], $data['interval_days'], $data['next_date'], $id]);
    }
    public static function deleteMaintenance($id)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare("DELETE FROM maintenance_schedule WHERE id=?");
        $stmt->execute([$id]);
    }

    // === Tax Schedule ===
    public static function allTax()
    {
        $pdo = Database::getInstance()->pdo();
        $sql = "SELECT s.*, v.plat, v.merk, v.foto_path, v.current_responsible, v.jenis as jenis_kendaraan, v.jenis  as category_name FROM tax_schedule s JOIN vehicles v ON v.id=s.vehicle_id ORDER BY s.next_date ASC";
        return $pdo->query($sql)->fetchAll();
    }
    public static function findTax($id)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare("SELECT * FROM tax_schedule WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public static function createTax($data)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('INSERT INTO tax_schedule (vehicle_id, tax_type, description, interval_days, next_date, estimated_cost) VALUES (?,?,?,?,?,?)');
        $stmt->execute([$data['vehicle_id'], $data['tax_type'], $data['description'], $data['interval_days'], $data['next_date'], $data['estimated_cost'] ?? 0]);
        return $pdo->lastInsertId();
    }
    public static function updateTax($id, $data)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare("UPDATE tax_schedule SET vehicle_id=?, tax_type=?, description=?, interval_days=?, next_date=?, estimated_cost=? WHERE id=?");
        $stmt->execute([$data['vehicle_id'], $data['tax_type'], $data['description'], $data['interval_days'], $data['next_date'], $data['estimated_cost'] ?? 0, $id]);
    }
    public static function deleteTax($id)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare("DELETE FROM tax_schedule WHERE id=?");
        $stmt->execute([$id]);
    }

    public static function updateTaxNextDate($id)
    {
        $pdo = Database::getInstance()->pdo();
        // Get current info
        $stmt = $pdo->prepare("SELECT next_date, interval_days FROM tax_schedule WHERE id=?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if ($row) {
            // Calculate new date: next_date + interval_days
            // NOTE: Using next_date (due date) preserves the cycle even if paid late.
            $newDate = date('Y-m-d', strtotime($row['next_date'] . ' + ' . $row['interval_days'] . ' days'));

            $upd = $pdo->prepare("UPDATE tax_schedule SET next_date=? WHERE id=?");
            $upd->execute([$newDate, $id]);
        }
    }

    // Keep legacy/widget methods
    public static function upcomingMaintenance($days = 30)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare("SELECT s.*, v.plat, v.merk FROM maintenance_schedule s JOIN vehicles v ON v.id=s.vehicle_id WHERE s.next_date <= DATE_ADD(CURDATE(), INTERVAL ? DAY) ORDER BY s.next_date ASC");
        $stmt->execute([$days]);
        return $stmt->fetchAll();
    }
    public static function upcomingTax($days = 30)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare("SELECT s.*, v.plat, v.merk FROM tax_schedule s JOIN vehicles v ON v.id=s.vehicle_id WHERE s.next_date <= DATE_ADD(CURDATE(), INTERVAL ? DAY) ORDER BY s.next_date ASC");
        $stmt->execute([$days]);
        return $stmt->fetchAll();
    }
}
