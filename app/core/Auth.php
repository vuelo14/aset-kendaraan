<?php
namespace Core; use Models\User;

class Auth {
    public static function check(){ return isset($_SESSION['user']); }
    public static function user(){ return $_SESSION['user'] ?? null; }
    public static function role(){ return $_SESSION['user']['role'] ?? 'guest'; }
    public static function requireLogin(){ if(!self::check()) { header('Location: /login'); exit; } }
    public static function requireAdmin(){ self::requireLogin(); if(self::role()!=='admin'){ http_response_code(403); echo '<h1>403</h1><p>Akses khusus admin.</p>'; exit; } }
}
