<?php
namespace Models;
use Core\Database;

class Budget
{
    public static function allCategories()
    {
        $pdo = Database::getInstance()->pdo();
        return $pdo->query('SELECT * FROM budget_categories ORDER BY id ASC')->fetchAll();
    }

    public static function findCategory($id)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('SELECT * FROM budget_categories WHERE id=?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function updateCategory($id, $data)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('UPDATE budget_categories SET max_unit_budget=?, max_total_budget=? WHERE id=?');
        // Handle empty strings as null for max_unit_budget
        $unit_budget = empty($data['max_unit_budget']) ? null : $data['max_unit_budget'];
        $stmt->execute([
            $unit_budget,
            $data['max_total_budget'],
            $id
        ]);
    }

    public static function getBudgetMonitoring()
    {
        $pdo = Database::getInstance()->pdo();
        return $pdo->query('SELECT * FROM vw_budget_monitoring ORDER BY id ASC')->fetchAll();
    }

    public static function getUnitBudgetMonitoring()
    {
        $pdo = Database::getInstance()->pdo();
        return $pdo->query('SELECT * FROM vw_unit_budget_monitoring ORDER BY vehicle_id ASC')->fetchAll();
    }
}
