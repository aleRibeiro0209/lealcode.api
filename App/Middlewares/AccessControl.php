<?php

namespace App\Middlewares;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AccessControl {
    
    private string $secretKey;

    public function __construct() {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $this->secretKey = $_ENV['JWT_SECRET'];
    }

    public function checkPermission(string $resource, string $action): void {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['erro' => 'Unauthorized']);
            exit;
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);

        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS512'));

            $decoded->permissions = json_decode($decoded->permissions);

            if (isset($decoded->permissions->$resource) && isset($decoded->permissions->$resource->$action) && $decoded->permissions->$resource->$action === true) {
                // Permissão concedida
                return;
            } else {
                http_response_code(403);
                echo json_encode(['erro' => 'Proibido! Usuário não tem permissão para acessar este recurso']);
                exit;
            }

        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(['erro' => 'Usuário não autorizado! Token inválido']);
            exit;
        }
    }

    public function generateToken($funcionario): string {

        $payload = [
            'sub' => $funcionario->idFuncionario,
            'permissions' => $funcionario->permissoes,
            'user' => $funcionario,
            'iat' => time(),
            'exp' => time() + 432000 // Token válido por 5 dias
        ];

        // Codificar o token JWT
        return JWT::encode($payload, $this->secretKey, 'HS512');
    }

}