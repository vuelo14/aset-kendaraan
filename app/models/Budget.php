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
        $sql = "SELECT v.id AS vehicle_id, v.plat, v.merk, v.tipe, v.jenis, v.status_penggunaan,
                v.current_responsible, v.budget_limit AS custom_budget,
                b.id AS budget_category_id, b.category_name,
                COALESCE(v.budget_limit, b.max_unit_budget, 0) AS max_unit_budget,
                COALESCE((SELECT SUM(m.biaya) FROM maintenance m WHERE m.vehicle_id = v.id AND YEAR(m.date) = YEAR(CURDATE())), 0) AS realisasi_unit,
                (COALESCE(v.budget_limit, b.max_unit_budget, 0) - COALESCE((SELECT SUM(m.biaya) FROM maintenance m WHERE m.vehicle_id = v.id AND YEAR(m.date) = YEAR(CURDATE())), 0)) AS sisa_budget_unit
                FROM vehicles v
                LEFT JOIN budget_categories b ON v.budget_category_id = b.id
                ORDER BY v.jenis DESC, v.status_penggunaan ASC, v.plat ASC";
        return $pdo->query($sql)->fetchAll();
    }

    public static function getVehicleBudget($vehicle_id)
    {
        $pdo = Database::getInstance()->pdo();
        $sql = "SELECT v.id AS vehicle_id, v.plat, v.merk, v.tipe, v.jenis, v.status_penggunaan,
                v.budget_limit AS custom_budget,
                b.id AS budget_category_id, b.category_name, b.max_total_budget AS max_category_budget,
                COALESCE(v.budget_limit, b.max_unit_budget, 0) AS max_unit_budget,
                COALESCE((SELECT SUM(m.biaya) FROM maintenance m WHERE m.vehicle_id = v.id AND YEAR(m.date) = YEAR(CURDATE())), 0) AS realisasi_unit,
                (COALESCE(v.budget_limit, b.max_unit_budget, 0) - COALESCE((SELECT SUM(m.biaya) FROM maintenance m WHERE m.vehicle_id = v.id AND YEAR(m.date) = YEAR(CURDATE())), 0)) AS sisa_budget_unit
                FROM vehicles v
                LEFT JOIN budget_categories b ON v.budget_category_id = b.id
                WHERE v.id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$vehicle_id]);
        return $stmt->fetch() ?: null;
    }

    public static function updateVehicleBudget($vehicle_id, $data)
    {
        $pdo = Database::getInstance()->pdo();
        $budget_limit = (!empty($data['budget_limit']) || (isset($data['budget_limit']) && is_numeric($data['budget_limit']) && $data['budget_limit'] !== ''))
            ? (float)$data['budget_limit']
            : null;

        $sql = "UPDATE vehicles SET budget_limit = ?";
        $params = [$budget_limit];

        if (isset($data['budget_category_id']) && $data['budget_category_id'] !== '') {
            $sql .= ", budget_category_id = ?";
            $params[] = (int)$data['budget_category_id'];
        }

        $sql .= " WHERE id = ?";
        $params[] = (int)$vehicle_id;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }
}


