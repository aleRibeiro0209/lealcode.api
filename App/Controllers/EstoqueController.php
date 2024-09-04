<?php

namespace App\Controllers;

use App\Core\Controller;

class EstoqueController extends Controller {

    public function index() {
        $estoqueModel = $this->getModel('Estoque');
        $estoqueList = $estoqueModel->findAll();

        http_response_code(200);
        echo json_encode($estoqueList);
    }

    public function show(int $id) {
        $estoqueModel = $this->getModel('Estoque');
        $veiculoEmEstoque = $estoqueModel->getId($id);

        if ($veiculoEmEstoque) {
            http_response_code(200);
            echo json_encode($veiculoEmEstoque);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Veiculo não encontrado no estoque']);
        }
    }

    // TODO: Adicionar métodos ao Controller de Estoque
}