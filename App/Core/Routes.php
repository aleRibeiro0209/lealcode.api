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
            'route' => '/veiculos'
        );

        $routes['Carroceria'] = array(
            'controller' => 'Carrocerias',
            'route' => '/carrocerias'
        );
        
        $routes['Funcionarios'] = array(
            'controller' => 'Funcionarios',
            'route' => '/funcionarios'
        );

        $routes['Cargos'] = array(
            'controller' => 'Cargos',
            'route' => '/cargos'
        );
        
        $routes['Logins'] = array(
            'controller' => 'Logins',
            'route' => '/logins'
        );

        $routes['Cadastros'] = array(
            'controller' => 'Funcionarios',
            'route' => '/cadastros'
        );

        $this->setRoutes($routes);
    }

    private function run(array $routes) {
        $auth = new AccessControl;

        foreach ($routes as $key => $route) {
            $urls = $this->getUrl();
            if ("/$urls[0]" == $route['route']) {
                $class = "App\\Controllers\\" . $route['controller'];
                $method = $_SERVER['REQUEST_METHOD'];

                if ($route['route'] == "/logins" && $method == 'POST') {
                    $controller = new $class;
                    return;
                } elseif ($route['route'] == "/cadastros" && $method == 'POST') {
                    $controller = new $class;
                    $controller->store();
                    return;
                }

                $auth->checkPermission($urls[0], $this->getActionByMethod($method));

                if (class_exists($class)) {
                    $controller = new $class;
                    switch ($method) {
                        case 'DELETE':
                            if (isset($urls[1])) {
                                $controller->destroy($urls[1]);
                            } else {
                                http_response_code(404);
                                echo json_encode(['erro' => 'Id não encontrado']);
                            }
                            break;
                        case 'GET':
                            if (isset($urls[1])) {
                                $controller->show($urls[1]);
                            } else {
                                $controller->index();
                            }
                            break;
                        case 'POST':
                            $controller->store();
                            break;
                        case 'PUT':
                            if (isset($urls[1])) {
                                $controller->update($urls[1]);
                            } else {
                                http_response_code(404);
                                echo json_encode(['erro' => 'Id não encontrado']);
                            }
                            break;
                        default:
                            http_response_code(405);
                            echo json_encode(['erro' => 'Verbo de requisição não suportado']);
                            break;
                    }
                    return;
                } else {
                    http_response_code(500);
                    echo json_encode(['erro' => 'Controller não encontrado']);
                    return;
                }
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