<?php

namespace App\Controllers;

use App\Core\Controller;

class Cargos extends Controller {

    public function index() {
        $cargoModel = $this->getModel('Cargo');
        $cargoList = $cargoModel->findAll();

        http_response_code(200);
        echo json_encode($cargoList);
    }

    public function show($id) {
        $cargoModel = $this->getModel('Cargo');
        $cargo = $cargoModel->getId($id);

        if ($cargo) {
            http_response_code(200);
            echo json_encode($cargo);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Cargo não cadastrado']);
        }
    }

    public function store() {
        $novoCargo = $this->getBodyRequest();
        
        $cargoModel = $this->getModel('Cargo');
        $cargo = $cargoModel->create($novoCargo);

        if ($cargo) {
            http_response_code(201);
            echo json_encode($cargo);
        } else {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao cadastrar cargo']);
        }
    }

    public function update($id) {
        $atualizacaoCargo = $this->getBodyRequest();

        $cargoModel = $this->getModel('Cargo');
        $cargo = $cargoModel->update($id, $atualizacaoCargo);

        if ($cargo) {
            http_response_code(200);
            echo json_encode($cargo);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Cargo não encontrado ou não atualizado']);
        }
    }

    public function destroy($id) {
        $cargoModel = $this->getModel('Cargo');
        $deleted = $cargoModel->delete($id);

        if($deleted) {
            http_response_code(200);
            echo json_encode(['success' => 'Cargo deletado com sucesso']);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Cargo não encontrado ou já deletado']);
        }
    }
}