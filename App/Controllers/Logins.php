<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middlewares\AccessControl;

class Logins extends Controller {

    public function __construct() {
        $this->run();
    }

    private function run() {
        $credenciais = $this->getBodyRequest();

        $funcionarioModel = $this->getModel('Funcionario');
        $funcionario = $funcionarioModel->getByCredentials($credenciais);

        if (isset($funcionario)) {
            $jwt = new AccessControl;
            $token = $jwt->generateToken($funcionario);

            echo json_encode([
                'token' => $token,
                'message' => 'Login realizado com sucesso'
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Email ou senha inválidos']);
        }
    }
}