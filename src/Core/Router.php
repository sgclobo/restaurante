<?php
namespace Core;

class Router {
    private $routes = [];

    public function add($route, $params = []) {
        // Convert route to regex: {controller}/{action} -> (?P<controller>[a-z-]+)/(?P<action>[a-z-]+)
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;
    }

    public function dispatch($url) {
        $url = $this->removeQueryStringVariables($url);

        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                $this->execute($params);
                return;
            }
        }

        // Default: 404
        $this->execute(['controller' => 'ErrorController', 'action' => 'notFound']);
    }

    private function execute($params) {
        $controllerName = $params['controller'];
        $actionName = $params['action'];
        
        // Namespace prefix
        $controllerClass = "Controllers\\" . $controllerName;

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass($params);
            if (method_exists($controller, $actionName)) {
                $controller->$actionName();
            } else {
                echo "Method $actionName not found in controller $controllerClass";
            }
        } else {
             // Fallback or simple view rendering if no controller
             echo "Controller $controllerClass not found";
        }
    }

    private function removeQueryStringVariables($url) {
        if ($url != '') {
            $parts = explode('&', $url, 2);
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        return $url;
    }
}
