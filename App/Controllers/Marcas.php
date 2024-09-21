<?php

namespace App\Controllers;

use App\Core\Controller;

class Marcas extends Controller {

    public function index() {
        $marcaModel = $this->getModel('Marca');
        $marcaList = $marcaModel->findAll();

        echo json_encode($marcaList);
    }

    public function show($id) {
        $marcaModel = $this->getModel('Marca');
        $marca = $marcaModel->getId($id);

        if ($marca) {
            http_response_code(200);
            echo json_encode($marca);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Marca não cadastrada']);
        }
    }

    public function store() {
        $novaMarca = $this->getBodyRequest();

        $marcaModel = $this->getModel('Marca');
        $marcaObj = $marcaModel->create($novaMarca);

        if ($marcaObj) {
            http_response_code(201);
            echo json_encode($marcaObj);
        } else {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao cadastrar marca']);
        }
    }

    public function update($id) {
        $atualizacaoMarca = $this->getBodyRequest();

        $marcaModel = $this->getModel('Marca');
        $marcaAtualizada = $marcaModel->update($id, $atualizacaoMarca);

        if($marcaAtualizada) {
            http_response_code(200);
            echo json_encode($marcaAtualizada);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Marca não encontrada ou não atualizada']);
        }
    }
    
    public function destroy($id) {
        $marcaModel = $this->getModel('Marca');
        $deleted = $marcaModel->delete($id);

        if($deleted) {
            http_response_code(200);
            echo json_encode(['success' => 'Marca deletada com sucesso']);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Marca não encontrada ou já deletada']);
        }
    }
    
}