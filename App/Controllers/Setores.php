<?php

namespace App\Controllers;

use App\Core\Controller;

class Setores extends Controller {

    public function index() {
        $setorModel = $this->getModel('Setor');
        $setorList = $setorModel->findAll();

        echo json_encode($setorList);
    }

    public function show($id) {
        $setorModel = $this->getModel('Setor');
        $setor = $setorModel->getId($id);

        if ($setor) {
            http_response_code(200);
            echo json_encode($setor);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Setor não cadastrado']);
        }
    }

    public function store() {
        $novoSetor = $this->getBodyRequest();

        $setorModel = $this->getModel('Setor');
        $setorObj = $setorModel->create($novoSetor);

        if ($setorObj) {
            http_response_code(201);
            echo json_encode($setorObj);
        } else {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao cadastrar setor']);
        }
    }

    public function update($id) {
        $atualizacaoSetor = $this->getBodyRequest();

        $setorModel = $this->getModel('Setor');
        $setorAtualizado = $setorModel->update($id, $atualizacaoSetor);

        if($setorAtualizado) {
            http_response_code(200);
            echo json_encode($setorAtualizado);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Setor não encontrado ou não atualizado']);
        }
    }
    
    public function destroy($id) {
        $setorModel = $this->getModel('Setor');
        $deleted = $setorModel->delete($id);

        if($deleted) {
            http_response_code(200);
            echo json_encode(['success' => 'Setor deletado com sucesso']);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Setor não encontrado ou já deletado']);
        }
    }
    
}