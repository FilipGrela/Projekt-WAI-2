<?php

class Router {
    private $routes = [];

    // Add a route to the routing table
    public function add($route, $action) {
        $this->routes[$route] = $action;
    }

    // Dispatch the requested URL to the appropriate controller and method
    public function dispatch($url) {
        // Remove query strings from the URL
        $url = parse_url($url, PHP_URL_PATH);

        if (array_key_exists($url, $this->routes)) {
            $action = $this->routes[$url];
            $controllerName = $action['controller'];
            $methodName = $action['method'];

            // Check if the specified controller and method exist
            if (file_exists(__DIR__ . "/../controllers/$controllerName.php")) {
                require_once __DIR__ . "/../controllers/$controllerName.php";

                $controller = new $controllerName();

                if (method_exists($controller, $methodName)) {
                    // Call the method on the controller
                    $controller->$methodName();
                } else {
                    $this->error404("Method $methodName not found in controller $controllerName");
                }
            } else {
                $this->error404("Controller $controllerName not found");
            }
        } else {
            $this->error404("Route not found for URL: " . htmlspecialchars($url));
        }
    }

    // Default 404 error handler
    private function error404($message) {
        http_response_code(404);
        echo "<h1>404 - Not Found</h1><p>$message</p>";
        exit;
    }
}