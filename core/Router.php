<?php
class Router
{
    private array $routes = [];

    public function get(string $path, string $controller, string $method): void
    {
        $this->routes['GET'][$path] = [
            'controller' => $controller,
            'method' => $method
        ];
    }

    public function post(string $path, string $controller, string $method): void
    {
        $this->routes['POST'][$path] = [
            'controller' => $controller,
            'method' => $method
        ];
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];

        // 🔥 FIXED URI HANDLING
        // $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove base path (like /cimsho/public)
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
        $basePath   = dirname($scriptName);


        // ********************** Solve the Problem ***************************
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Auto-detect project folder (like /cimsho)
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
        $projectPath = dirname(dirname($scriptName)); // go up from /public/index.php

        if ($projectPath !== '/' && strpos($uri, $projectPath) === 0) {
            $uri = substr($uri, strlen($projectPath));
        }

        $uri = '/' . trim($uri, '/');
        $uri = $uri === '//' ? '/' : $uri;
        // *************************************************

        if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        $uri = '/' . trim($uri, '/');
        $uri = $uri === '//' ? '/' : $uri;

        // ── Exact match ─────────────────────────────
        if (isset($this->routes[$method][$uri])) {
            $this->callRoute($this->routes[$method][$uri], []);
            return;
        }

        // ── Pattern match (e.g. /product/123) ───────
        foreach ($this->routes[$method] ?? [] as $route => $action) {
            $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $route);

            if (preg_match('#^' . $pattern . '$#', $uri, $matches)) {
                array_shift($matches);
                $this->callRoute($action, $matches);
                return;
            }
        }

        // ── 404 ─────────────────────────────────────
        http_response_code(404);
        require APP_ROOT . '/app/views/partials/404.php';
    }

    private function callRoute(array $action, array $params): void
    {
        $controllerFile = APP_ROOT . '/app/controllers/' . $action['controller'] . '.php';

        if (!file_exists($controllerFile)) {
            die('Controller not found: ' . $action['controller']);
        }

        require_once $controllerFile;

        $controller = new $action['controller']();

        if (!method_exists($controller, $action['method'])) {
            die('Method not found: ' . $action['method']);
        }

        $controller->{$action['method']}(...$params);
    }
}
