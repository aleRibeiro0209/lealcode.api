<?php

namespace App\Core;

use App\Middlewares\AccessControl;

class Routes {
    
    private array $routes;

    public function __construct(){
        $this->init();
        $this->run($this->getRoutes());
    }

    private function setRoutes(array $routes) {
        $this->routes = $routes;
    }

    private function getRoutes(): array {
        return $this->routes;
    }

    private function init() {

        $routes = [];

        $routes['Veiculo'] = array(
            'controller' => 'Veiculos',
            'route' => '/veiculos',
            'permission' => 1
        );

        $routes['Carroceria'] = array(
            'controller' => 'Carrocerias',
            'route' => '/carrocerias',
            'permission' => 1
        );
        
        $routes['Funcionarios'] = array(
            'controller' => 'Funcionarios',
            'route' => '/funcionarios',
            'permission' => 1
        );

        $routes['Cargos'] = array(
            'controller' => 'Cargos',
            'route' => '/cargos',
            'permission' => 1
        );
        
        $routes['Logins'] = array(
            'controller' => 'LoginController',
            'route' => '/login',
            'permission' => 0
        );

        $routes['Estoque'] = array(
            'controller' => 'EstoqueController',
            'route' => '/estoque',
            'permission' => 0
        );

        $this->setRoutes($routes);
    }

    private function run(array $routes) {
        foreach ($routes as $key => $route) {
            $urls = $this->getUrl();
            if ("/$urls[0]" == $route['route']) {
                $class = "App\\Controllers\\" . $route['controller'];
                $method = $_SERVER['REQUEST_METHOD'];
                $action = '';

                if ($route['permission']) {
                    $auth = new AccessControl;
                    $auth->checkPermission($urls[0], $this->getActionByMethod($method));
                }

                switch ($method) {
                    case 'DELETE':
                        $action = 'destroy';
                        break;
                    case 'GET':
                        if (isset($urls[1])) {
                            $action = 'show';
                        } else {
                            $action = 'index';
                        }
                        break;
                    case 'POST':
                        $action = 'store';
                        break;
                    case 'PUT':
                        $action = 'update';
                        break;
                    default:
                        http_response_code(405);
                        echo json_encode(['erro' => 'Verbo de requisição não suportado']);
                        break;
                }

                if (method_exists($class, $action)) {
                    $controller = new $class;
                    isset($urls[1]) ? $controller->$action($urls[1]) : $controller->$action();
                } else {
                    http_response_code(404);
                    echo json_encode(['erro' => 'Método não encontrado']);
                }

                return;
            }
        }

        http_response_code(404);
        echo json_encode(['erro' => 'Rota não encontrada']);
    }

    private function getUrl(): array {
        $url = parse_url((strtolower($_SERVER['REQUEST_URI'])), PHP_URL_PATH);
        $urls = explode('/', trim($url, '/'));
        return $urls;
    }

    private function getActionByMethod(string $method): string {
        switch ($method) {
            case 'POST':
                return 'create';
            case 'PUT':
                return 'edit';
            case 'DELETE':
                return 'delete';
            case 'GET':
            default:
                return 'view';
        }
    }
}