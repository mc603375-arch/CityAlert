<?php

class Router
{
    // -------------------------------------------------------
    // Tableau des routes enregistrées
    // -------------------------------------------------------
    private array $routes = [];

    // -------------------------------------------------------
    // Ajouter une route GET
    // -------------------------------------------------------
    public function get(string $path, string $controller, string $method): void
    {
        $this->routes['GET'][$path] = [
            'controller' => $controller,
            'method'     => $method
        ];
    }

    // -------------------------------------------------------
    // Ajouter une route POST
    // -------------------------------------------------------
    public function post(string $path, string $controller, string $method): void
    {
        $this->routes['POST'][$path] = [
            'controller' => $controller,
            'method'     => $method
        ];
    }

    // -------------------------------------------------------
    // Dispatcher — trouve et exécute la bonne route
    // -------------------------------------------------------
    public function dispatch(): void
    {
        // Récupère la méthode HTTP (GET ou POST)
        $httpMethod = $_SERVER['REQUEST_METHOD'];

        // Récupère l'URL demandée
        $uri = $_SERVER['REQUEST_URI'];

        // Supprime les paramètres GET de l'URL (?id=1)
        $uri = strtok($uri, '?');

        // Supprime le chemin de base ET un éventuel index.php
        $uri = str_replace('/CityAlert/index.php', '', $uri);
        $uri = str_replace('/CityAlert', '', $uri);

        // Si l'URI est vide ou juste "/", on affiche login par défaut
        if ($uri === '' || $uri === '/') {
            $uri = '/login';
        }

        // Cherche la route correspondante
        if (isset($this->routes[$httpMethod][$uri])) {
            $route      = $this->routes[$httpMethod][$uri];
            $controller = new $route['controller']();
            $method     = $route['method'];
            $controller->$method();
        } else {
            $this->notFound();
        }
    }

    // -------------------------------------------------------
    // Page 404
    // -------------------------------------------------------
    private function notFound(): void
    {
        http_response_code(404);
        echo "<h1>404 - Page non trouvée</h1>";
    }
}