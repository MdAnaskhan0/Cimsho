<?php
class Router {
    private array $routes = [];

    public function get(string $p, string $c, string $m): void  { $this->routes['GET'][$p]  = [$c,$m]; }
    public function post(string $p, string $c, string $m): void { $this->routes['POST'][$p] = [$c,$m]; }

    public function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $base   = parse_url(APP_URL, PHP_URL_PATH);
        $path   = '/'.ltrim(str_replace($base,'',$uri),'/');
        if($path===''||$path==='/index.php') $path='/';

        // Exact match
        if(isset($this->routes[$method][$path])){
            $this->run(...$this->routes[$method][$path]);
            return;
        }

        // Pattern match (for :id params)
        foreach($this->routes[$method]??[] as $route=>$handler){
            $pattern = preg_replace('#:([a-z_]+)#','([^/]+)',$route);
            if(preg_match('#^'.$pattern.'$#',$path,$m)){
                array_shift($m);
                $_GET['_params'] = $m;
                $this->run(...$handler);
                return;
            }
        }

        http_response_code(404);
        require __DIR__.'/../app/views/404.php';
    }

    private function run(string $ctrl, string $action): void {
        $file = __DIR__.'/../app/controllers/'.$ctrl.'.php';
        if(!file_exists($file)){ http_response_code(404); die("Controller not found: $ctrl"); }
        require_once $file;
        $obj = new $ctrl();
        $params = $_GET['_params'] ?? [];
        $obj->$action(...$params);
    }
}
