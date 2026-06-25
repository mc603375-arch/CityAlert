<?php
class Router
{
    private array $routes = [];

    public function get(string $url, string $ctrl, string $action): void
    {
        $this->routes['GET'][$url] = compact('ctrl', 'action');
    }

    public function post(string $url, string $ctrl, string $action): void
    {
        $this->routes['POST'][$url] = compact('ctrl', 'action');
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Supprime le préfixe /CityAlert
        if (str_starts_with($uri, BASE_PATH)) {
            $uri = substr($uri, strlen(BASE_PATH));
        }

        // Supprime aussi /index.php si présent
        if (str_starts_with($uri, '/index.php')) {
            $uri = substr($uri, strlen('/index.php'));
        }

        $uri = '/' . trim($uri, '/');
        if ($uri === '/') $uri = '/login';

        if (isset($this->routes[$method][$uri])) {
            $r    = $this->routes[$method][$uri];
            $ctrl = new $r['ctrl']();
            $ctrl->{$r['action']}();
            return;
        }

        http_response_code(404);
        $f = VIEW_PATH . '/errors/404.php';
        file_exists($f) ? require $f : print "<h1>404 — Page introuvable</h1>";
    }
}