<?php

namespace App\Controllers;

use App\Core\Controller;

class Notificacoes extends Controller {

    public function index() {
        $notificacaoModel = $this->getModel('Notificacao');
        $notificacaoList = $notificacaoModel->findAll();

        http_response_code(200);
        echo json_encode($notificacaoList);
    }

    public function show($id) {
        $notificacaoModel = $this->getModel('Notificacao');
        $notificacao = $notificacaoModel->getId($id);

        if ($notificacao) {
            http_response_code(200);
            echo json_encode($notificacao);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Notificação não cadastrada ou deletada']);
        }
    }

    public function store() {
        $novaNotificacao = $this->getBodyRequest();
        
        $notificacaoModel = $this->getModel('Notificacao');
        $notificacaoObj = $notificacaoModel->create($novaNotificacao, $this->funcionario->idFuncionario);

        if ($notificacaoObj) {
            http_response_code(201);
            echo json_encode($notificacaoObj);
        } else {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao cadastrar notificação']);
        }
    }

    public function destroy($id) {
        $notificacaoModel = $this->getModel('Notificacao');
        $deleted = $notificacaoModel->delete($id);

        if($deleted) {
            http_response_code(200);
            echo json_encode(['success' => 'Notificação deletada com sucesso']);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Notificação não encontrada ou já deletada']);
        }
    }
}