<?php

namespace SellNow\Config;

class Router
{
    private array $routes = [];
    private $notFoundHandler;

    public function get(string $path, string|callable $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, string|callable $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function any(string $path, string|callable $handler): void
    {
        $this->addRoute('ANY', $path, $handler);
    }

    private function addRoute(string $method, string $path, string|callable $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function notFound(callable $handler): void
    {
        $this->notFoundHandler = $handler;
    }

    public function dispatch(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['method'] !== 'ANY' && $route['method'] !== $method) {
                continue;
            }

            $pattern = $this->convertToRegex($route['path']);

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $this->executeHandler($route['handler'], $matches);
                return;
            }
        }

        if ($this->notFoundHandler) {
            call_user_func($this->notFoundHandler);
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }

    private function executeHandler(string|callable $handler, array $params = []): void
    {
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
            return;
        }

        // Handle "Controller@method" syntax
        if (is_string($handler) && str_contains($handler, '@')) {
            [$controllerClass, $method] = explode('@', $handler);

            $fullClass = "SellNow\\Controllers\\{$controllerClass}";

            if (class_exists($fullClass)) {
                // Check if controller needs Container (has custom constructor)
                $reflection = new \ReflectionClass($fullClass);
                $constructor = $reflection->getConstructor();

                if ($constructor && $constructor->getNumberOfParameters() > 0) {
                    // Controller has custom constructor, pass Container
                    $container = new \SellNow\Container();
                    $controller = new $fullClass($container);
                } else {
                    // Controller uses default constructor
                    $controller = new $fullClass();
                }

                call_user_func_array([$controller, $method], $params);
                return;
            }
        }

        throw new \Exception("Invalid handler: {$handler}");
    }

    private function convertToRegex(string $path): string
    {
        $path = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $path);
        return '#^' . $path . '$#';
    }
}