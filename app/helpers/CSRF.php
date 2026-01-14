<?php
namespace Helpers;

class CSRF {
    public static function token(){ if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
    public static function check($t){ return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'],$t); }
}
