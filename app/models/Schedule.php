<?php
namespace Models; use Core\Database;

class Schedule {
    public static function upcomingMaintenance($days=30){ $pdo=Database::getInstance()->pdo(); $stmt=$pdo->prepare("SELECT s.*, v.plat, v.merk FROM maintenance_schedule s JOIN vehicles v ON v.id=s.vehicle_id WHERE s.next_date <= DATE_ADD(CURDATE(), INTERVAL ? DAY) ORDER BY s.next_date ASC"); $stmt->execute([$days]); return $stmt->fetchAll(); }
    public static function upcomingTax($days=30){ $pdo=Database::getInstance()->pdo(); $stmt=$pdo->prepare("SELECT s.*, v.plat, v.merk FROM tax_schedule s JOIN vehicles v ON v.id=s.vehicle_id WHERE s.next_date <= DATE_ADD(CURDATE(), INTERVAL ? DAY) ORDER BY s.next_date ASC"); $stmt->execute([$days]); return $stmt->fetchAll(); }
    public static function createMaintenance($data){ $pdo=Database::getInstance()->pdo(); $stmt=$pdo->prepare('INSERT INTO maintenance_schedule (vehicle_id, description, interval_days, next_date) VALUES (?,?,?,?)'); $stmt->execute([$data['vehicle_id'],$data['description'],$data['interval_days'],$data['next_date']]); }
    public static function createTax($data){ $pdo=Database::getInstance()->pdo(); $stmt=$pdo->prepare('INSERT INTO tax_schedule (vehicle_id, description, interval_days, next_date) VALUES (?,?,?,?)'); $stmt->execute([$data['vehicle_id'],$data['description'],$data['interval_days'],$data['next_date']]); }
}
