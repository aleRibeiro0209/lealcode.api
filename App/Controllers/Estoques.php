<?php

namespace App\Controllers;

use App\Core\Controller;

class Estoques extends Controller {

    public function index() {
        $estoqueModel = $this->getModel('Estoque');
        $estoqueList = $estoqueModel->findAll();

        http_response_code(200);
        echo json_encode($estoqueList);
    }

    // TODO: Adicionar métodos ao Controller de Estoque
}