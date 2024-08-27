<?php

namespace App\Controllers;

use App\Core\Controller;

class Carrocerias extends Controller {

    public function index() {
        $carroceriaModel = $this->getModel('Carroceria');
        $carroceriaList = $carroceriaModel->findAll();

        echo json_encode($carroceriaList);
    }

    public function show($id) {
        $carroceriaModel = $this->getModel('Carroceria');
        $carroceria = $carroceriaModel->getId($id);

        if ($carroceria) {
            http_response_code(200);
            echo json_encode($carroceria);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Carroceria não cadastrada']);
        }
    }

    public function store() {
        $novaCarroceria = $this->getBodyRequest();
        
        $carroceriaModel = $this->getModel('Carroceria');
        $carroceriaObj = $carroceriaModel->create($novaCarroceria);

        if ($carroceriaObj) {
            http_response_code(201);
            echo json_encode($carroceriaObj);
        } else {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao cadastrar carroceria']);
        }
    }

    public function update($id) {
        $atualizacaoCarroceria = $this->getBodyRequest();

        $carroceriaModel = $this->getModel('Carroceria');
        $carroceriaAtualizada = $carroceriaModel->update($id, $atualizacaoCarroceria);

        if($carroceriaAtualizada) {
            http_response_code(200);
            echo json_encode($carroceriaAtualizada);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Carroceria não encontrada ou não atualizada']);
        }
    }
    
    public function destroy($id) {
        $carroceriaModel = $this->getModel('Carroceria');
        $deleted = $carroceriaModel->delete($id);

        if($deleted) {
            http_response_code(200);
            echo json_encode(['success' => 'Carroceria deletada com sucesso']);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Carroceria não encontrada ou já deletada']);
        }
    }
    
}