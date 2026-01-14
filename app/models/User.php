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
}
