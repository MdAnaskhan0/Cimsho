<?php
class Router
{
    private array $routes = [];

    public function get(string $path, string $controller, string $method): void
    {
        $this->routes['GET'][$path] = ['controller' => $controller, 'method' => $method];
    }

    public function post(string $path, string $controller, string $method): void
    {
        $this->routes['POST'][$path] = ['controller' => $controller, 'method' => $method];
    }

    public function dispatch(): void
    {
        $method  = $_SERVER['REQUEST_METHOD'];
        $uri     = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $base    = parse_url(APP_URL, PHP_URL_PATH);
        $path    = '/' . ltrim(str_replace($base, '', $uri), '/');
        $path    = $path === '' ? '/' : $path;

        // Try to match route with parameters
        foreach ($this->routes[$method] ?? [] as $routePath => $routeConfig) {
            // Convert route pattern to regex
            $pattern = preg_replace('/\{[a-z]+\}/', '([^/]+)', $routePath);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches); // Remove the full match

                $controllerClass = $routeConfig['controller'];
                $actionName = $routeConfig['method'];

                $controllerFile = __DIR__ . '/../app/controllers/' . $controllerClass . '.php';
                if (!file_exists($controllerFile)) {
                    http_response_code(404);
                    die("Controller not found: " . $controllerClass);
                }

                require_once $controllerFile;
                $controller = new $controllerClass();

                // Call controller method with parameters
                call_user_func_array([$controller, $actionName], $matches);
                return;
            }
        }

        // Fallback: try to match controller from path (for non-registered routes)
        $segments   = explode('/', trim($path, '/'));
        $ctrlName   = ucfirst($segments[0] ?: 'Dashboard') . 'Controller';
        $actionName = $segments[1] ?? 'index';
        $ctrlFile   = __DIR__ . '/../app/controllers/' . $ctrlName . '.php';

        if (file_exists($ctrlFile)) {
            require_once $ctrlFile;
            $ctrl = new $ctrlName();
            if (method_exists($ctrl, $actionName)) {
                // Pass remaining segments as parameters
                $params = array_slice($segments, 2);
                call_user_func_array([$ctrl, $actionName], $params);
                return;
            }
        }

        // 404 Not Found
        http_response_code(404);
        require __DIR__ . '/../app/views/pages/404.php';
    }
}
