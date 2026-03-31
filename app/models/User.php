<?php

namespace Models;

use Core\Database;

class User
{
    public static function findByUsername($username)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username=? LIMIT 1');
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
    public static function all()
    {
        $pdo = Database::getInstance()->pdo();
        return $pdo->query('SELECT id,name,username,role FROM users ORDER BY id DESC')->fetchAll();
    }
    public static function create($data)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('INSERT INTO users (name, username, password, role) VALUES (?,?,?,?)');
        $stmt->execute([$data['name'], $data['username'], password_hash($data['password'], PASSWORD_DEFAULT), $data['role']]);
        return $pdo->lastInsertId();
    }
    public static function find($id)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id=?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public static function update($id, $data)
    {
        $pdo = Database::getInstance()->pdo();

        // Cek apakah password diisi atau kosong
        if (!empty($data['password'])) {
            // Jika password diisi, update password juga
            $sql = "UPDATE users SET name=?, username=?, role=?, password=? WHERE id=?";
            $params = [
                $data['name'],
                $data['username'],
                $data['role'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $id
            ];
        } else {
            // Jika password kosong, jangan ubah password lama
            $sql = "UPDATE users SET name=?, username=?, role=? WHERE id=?";
            $params = [
                $data['name'],
                $data['username'],
                $data['role'],
                $id
            ];
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }
    public static function delete($id)
    {
        $pdo = Database::getInstance()->pdo();
        $stmt = $pdo->prepare('DELETE FROM users WHERE id=?');
        $stmt->execute([$id]);
    }
}
