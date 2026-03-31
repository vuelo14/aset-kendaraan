<?php
namespace Models;
use Core\Database;

class Komponen {
    public static function all() {
        $pdo = Database::getInstance()->pdo();
        return $pdo->query('SELECT * FROM komponen ORDER BY jenis, nama')->fetchAll();
    }

    public static function find($id) {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('SELECT * FROM komponen WHERE id=?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data) {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('INSERT INTO komponen (nama, jenis, satuan, harga) VALUES (?,?,?,?)');
        $stmt->execute([
            $data['nama'],
            $data['jenis'],
            $data['satuan'],
            $data['harga']
        ]);
        return $pdo->lastInsertId();
    }

    public static function update($id, $data) {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('UPDATE komponen SET nama=?, jenis=?, satuan=?, harga=? WHERE id=?');
        $stmt->execute([
            $data['nama'],
            $data['jenis'],
            $data['satuan'],
            $data['harga'],
            $id
        ]);
    }

    public static function delete($id) {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('DELETE FROM komponen WHERE id=?');
        $stmt->execute([$id]);
    }
}
