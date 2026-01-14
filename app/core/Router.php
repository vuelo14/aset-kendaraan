<?php
namespace Core;

class Router {
    private $routes = ['GET'=>[], 'POST'=>[]];
    public function get($p,$h){ $this->routes['GET'][$p]=$h; }
    public function post($p,$h){ $this->routes['POST'][$p]=$h; }
    public function dispatch(){
        $m = $_SERVER['REQUEST_METHOD'];
        $r = $_GET['route'] ?? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if(!$r) $r='/'; $r = rtrim($r,'/') ?: '/';
        $h = $this->routes[$m][$r] ?? null;
        if(!$h){ http_response_code(404); echo '<h1>404 Not Found</h1>'; return; }
        [$c,$a] = explode('@',$h);
        $class = 'Controllers\\' . $c;
        $obj = new $class();
        if(!method_exists($obj,$a)){ http_response_code(500); echo '<h1>500</h1><p>Aksi tidak ditemukan.</p>'; return; }
        return $obj->$a();
    }
}

