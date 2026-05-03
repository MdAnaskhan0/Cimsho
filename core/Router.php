<?php
class Router
{
    private $routes = [];

    public function get($path, $handler)
    {
        $this->routes['GET'][$path] = $handler;
    }
    public function post($path, $handler)
    {
        $this->routes['POST'][$path] = $handler;
    }

    // public function dispatch($uri, $method) {
    //     $uri = parse_url($uri, PHP_URL_PATH);
    //     $uri = rtrim($uri, '/') ?: '/';
    //     // Remove base path
    //     $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    //     if ($base && strpos($uri, $base) === 0) $uri = substr($uri, strlen($base));
    //     $uri = $uri ?: '/';

    //     foreach ($this->routes[$method] ?? [] as $route => $handler) {
    //         $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route);
    //         $pattern = '#^' . $pattern . '$#';
    //         if (preg_match($pattern, $uri, $matches)) {
    //             array_shift($matches);
    //             [$controller, $action] = explode('@', $handler);
    //             require_once BASE_PATH . "/app/controllers/{$controller}.php";
    //             $obj = new $controller();
    //             return call_user_func_array([$obj, $action], $matches);
    //         }
    //     }
    //     http_response_code(404);
    //     require BASE_PATH . '/app/views/404.php';
    // }

    public function dispatch($uri, $method)
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';
        // Remove base path
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        if ($base && strpos($uri, $base) === 0) $uri = substr($uri, strlen($base));
        $uri = $uri ?: '/';

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                [$controller, $action] = explode('@', $handler);

                // Check if it's an admin controller (starts with 'Admin')
                if (strpos($controller, 'Admin') === 0) {
                    // Load the combined AdminControllers.php file
                    require_once BASE_PATH . "/app/controllers/AdminControllers.php";
                } else {
                    // Load regular controller files
                    require_once BASE_PATH . "/app/controllers/{$controller}.php";
                }

                $obj = new $controller();
                return call_user_func_array([$obj, $action], $matches);
            }
        }
        http_response_code(404);
        require BASE_PATH . '/app/views/404.php';
    }
}
