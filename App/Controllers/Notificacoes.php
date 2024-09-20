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
            echo json_encode(['erro' => 'Notificação não cadastrada']);
        }
    }

    public function store() {
        $novaNotificacao = $this->getBodyRequest();
        
        $notificacaoModel = $this->getModel('Notificacao');
        $funcionarioModel = $this->getModel('Funcionario');
        $novaNotificacao->matricula = $funcionarioModel->findId($novaNotificacao->matricula);

        if ($novaNotificacao->matricula) {
            $notificacaoObj = $notificacaoModel->create($novaNotificacao);
            if ($notificacaoObj) {
                http_response_code(201);
                echo json_encode($notificacaoObj);
            } else {
                http_response_code(500);
                echo json_encode(['erro' => 'Erro ao cadastrar notificação']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Matrícula não cadastrada ou não encontrada']);
        }
    }

    public function update($id) {
        $atualizacaoNotificacao = $this->getBodyRequest();

        $notificacaoModel = $this->getModel('Notificacao');
        $funcionarioModel = $this->getModel('Funcionario');
        $atualizacaoNotificacao->matricula = $funcionarioModel->findId($atualizacaoNotificacao->matricula);

        if($atualizacaoNotificacao->matricula) {
            $notificacaoAtualizado = $notificacaoModel->update($id, $atualizacaoNotificacao);

            if ($notificacaoAtualizado) {
                http_response_code(200);
                echo json_encode($notificacaoAtualizado);
            } else {
                http_response_code(404);
                echo json_encode(['erro' => 'Notificação não encontrada ou não atualizada']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Matrícula não cadastrada']);
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