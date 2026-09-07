<?php

namespace Models;

use Core\Database;

class Vehicle
{
    public static function all($filters = [])
    {
        $pdo = Database::getInstance()->pdo();
        $sql = 'SELECT * FROM vehicles WHERE 1=1';
        $params = [];
        if (!empty($filters['jenis'])) {
            $sql .= ' AND jenis=?';
            $params[] = $filters['jenis'];
        }
        if (!empty($filters['status_kendaraan'])) {
            $sql .= ' AND status_kendaraan=?';
            $params[] = $filters['status_kendaraan'];
        }
        if (!empty($filters['status_pajak'])) {
            $sql .= ' AND pajak_status=?';
            $params[] = $filters['status_pajak'];
        }
        if (!empty($filters['status_penggunaan'])) {
            $sql .= ' AND status_penggunaan=?';
            $params[] = $filters['status_penggunaan'];
        }
        if (!empty($filters['penanggung'])) {
            $sql .= ' AND current_responsible LIKE ?';
            $params[] = '%' . $filters['penanggung'] . '%';
        }
        $sql .= ' ORDER BY id DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    public static function find($id)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('SELECT * FROM vehicles WHERE id=?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public static function create($data)
    {
        $pdo = Database::getInstance()->pdo();
        $budget_cat = !empty($data['budget_category_id']) ? $data['budget_category_id'] : null;
        $budget_limit = (!empty($data['budget_limit']) || (isset($data['budget_limit']) && is_numeric($data['budget_limit']) && $data['budget_limit'] !== '')) ? (float)$data['budget_limit'] : null;

        $stmt = $pdo->prepare('INSERT INTO vehicles (plat, merk, tipe, tahun, jenis, status_penggunaan, status_kendaraan, foto_path, kondisi, current_responsible, budget_category_id, budget_limit) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute([
            $data['plat'], $data['merk'], $data['tipe'], $data['tahun'], $data['jenis'],
            $data['status_penggunaan'], $data['status_kendaraan'], $data['foto_path'],
            $data['kondisi'], $data['current_responsible'],
            $budget_cat, $budget_limit
        ]);
        return $pdo->lastInsertId();
    }

    public static function update($id, $data)
    {
        $pdo = Database::getInstance()->pdo();
        $fields = [
            'plat', 'merk', 'tipe', 'tahun', 'jenis',
            'status_penggunaan', 'status_kendaraan', 'foto_path', 'kondisi', 'current_responsible'
        ];
        $set = [];
        $params = [];
        foreach ($fields as $f) {
            $set[] = "$f=?";
            $params[] = $data[$f];
        }

        if (array_key_exists('budget_category_id', $data)) {
            $set[] = "budget_category_id=?";
            $params[] = !empty($data['budget_category_id']) ? $data['budget_category_id'] : null;
        }

        if (array_key_exists('budget_limit', $data)) {
            $set[] = "budget_limit=?";
            $params[] = (!empty($data['budget_limit']) || (isset($data['budget_limit']) && is_numeric($data['budget_limit']) && $data['budget_limit'] !== '')) ? (float)$data['budget_limit'] : null;
        }

        $params[] = $id;
        $sql = 'UPDATE vehicles SET ' . implode(', ', $set) . ' WHERE id=?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }

    public static function delete($id)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('DELETE FROM vehicles WHERE id=?');
        $stmt->execute([$id]);
    }
}
