<?php

namespace App\Controllers;

use App\Core\Controller;

class Vendas extends Controller {

    public function index() {
        $solicitacaoParametrizada = new \stdClass;

        $solicitacaoParametrizada->limite = isset($_GET['limite']) && $_GET['limite'] != '' ? (int)$_GET['limite'] : 10;
        $solicitacaoParametrizada->pagina = isset($_GET['pagina']) && $_GET['pagina'] != '' ? (int)$_GET['pagina'] : 1;
        $solicitacaoParametrizada->offset = ($solicitacaoParametrizada->pagina - 1) * $solicitacaoParametrizada->limite;

        $vendaModel = $this->getModel('Venda');
        $vendaList = $vendaModel->findAll($solicitacaoParametrizada);
        
        http_response_code(200);
        echo json_encode($vendaList);
    }

    public function show($id) {
        $vendaModel = $this->getModel('Venda');
        $venda = $vendaModel->getId($id);

        if ($venda) {
            http_response_code(200);
            echo json_encode($venda);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Venda não cadastrada']);
        }
    }

    public function store() {
        $novaVenda = $this->getBodyRequest();
        
        $vendaModel = $this->getModel('Venda');

        $vendaObj = $vendaModel->create($novaVenda);

        if ($vendaObj) {
            http_response_code(201);
            echo json_encode(['venda' => $vendaObj, 'message' => 'Venda cadastrada com sucesso']);
        } else {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao cadastrar venda']);
        }
    }

    public function update($id) {
        $atualizacaoVenda = $this->getBodyRequest();

        $vendaModel = $this->getModel('Venda');
        $vendaAtualizado = $vendaModel->update($id, $atualizacaoVenda);

        if ($vendaAtualizado) {
            http_response_code(200);
            echo json_encode($vendaAtualizado);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Venda não encontrada ou não atualizada']);
        }
    }
    
}