<?php
declare(strict_types=1);

namespace VetVida\Core;

final class Router {
    private array $routes = [];

    public function add(string $method, string $path, array $handler, array $middlewares = []): void {
        $normalizedPath = '/' . trim($path, '/');
        $this->routes[strtoupper($method)][$normalizedPath] = [
            'handler'     => $handler,
            'middlewares' => $middlewares
        ];
    }

    public function dispatch(string $method, string $uri): void {
        $path = (string)parse_url($uri, PHP_URL_PATH);
        $cleanPath = '/' . trim($path, '/');
        $httpMethod = strtoupper($method);

        if (isset($this->routes[$httpMethod][$cleanPath])) {
            $route = $this->routes[$httpMethod][$cleanPath];

            foreach ($route['middlewares'] as $middleware) {
                [$middlewareClass, $middlewareMethod] = $middleware;
                $middlewareClass::$middlewareMethod();
            }

            [$controllerClass, $actionMethod] = $route['handler'];
            (new $controllerClass())->$actionMethod();
            return;
        }

        http_response_code(404);
        echo "<!DOCTYPE html><html lang='pt-BR'><head><meta charset='UTF-8'><title>404 - Pagina Nao Encontrada</title>"
           . "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'></head>"
           . "<body class='bg-light text-center py-5'><div class='container'>"
           . "<h1 class='display-3 text-danger fw-bold'>404</h1>"
           . "<p class='lead'>A pagina ou rota solicitada nao foi encontrada: <code>" . htmlspecialchars($cleanPath) . "</code></p>"
           . "<a href='/' class='btn btn-primary'>Retornar ao Painel</a></div></body></html>";
    }
}
