<?php
namespace EkaCore;

class EkaRouter
{
    private array $routes = [];

    public function get(string $path, callable|array $handler, array $middlewares = []): void
    {
        $this->addRoute('GET', $path, $handler, $middlewares);
    }

    public function post(string $path, callable|array $handler, array $middlewares = []): void
    {
        $this->addRoute('POST', $path, $handler, $middlewares);
    }

    private function addRoute(string $method, string $path, callable|array $handler, array $middlewares): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        foreach ($this->routes as $route) {
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if ($route['method'] === $method && preg_match($pattern, $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                if (!empty($route['middlewares'])) {
                    foreach ($route['middlewares'] as $middleware) {
                        $middlewareInstance = new $middleware();
                        if (!$middlewareInstance->handle()) {
                            return;
                        }
                    }
                }

                if (is_array($route['handler'])) {
                    $controllerName = $route['handler'][0];
                    $action = $route['handler'][1];
                    $controller = new $controllerName();
                    
                    $request = new EkaRequest($params, $_POST, $_GET);
                    $response = new EkaResponse();
                    
                    call_user_func([$controller, $action], $request, $response);
                } else {
                    $request = new EkaRequest($params, $_POST, $_GET);
                    $response = new EkaResponse();
                    call_user_func($route['handler'], $request, $response);
                }
                return;
            }
        }

        http_response_code(404);
        require __DIR__ . '/../app/Views/errors/404.php';
    }
}
