<?php

namespace App\Controllers;

use App\Core\Controller;

class Clientes extends Controller {

    public function index() {
        $solicitacaoParametrizada = new \stdClass;

        $solicitacaoParametrizada->limite = isset($_GET['limite']) && $_GET['limite'] != '' ? (int)$_GET['limite'] : 10;
        $solicitacaoParametrizada->pagina = isset($_GET['pagina']) && $_GET['pagina'] != '' ? (int)$_GET['pagina'] : 1;
        $solicitacaoParametrizada->offset = ($solicitacaoParametrizada->pagina - 1) * $solicitacaoParametrizada->limite;

        $clienteModel = $this->getModel('Cliente');
        $clienteList = $clienteModel->findAll($solicitacaoParametrizada);
        
        http_response_code(200);
        echo json_encode($clienteList);
    }

    public function show($id) {
        $clienteModel = $this->getModel('Cliente');
        $cliente = $clienteModel->getId($id);

        if ($cliente) {
            http_response_code(200);
            echo json_encode($cliente);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Cliente não cadastrado']);
        }
    }

    public function store() {
        $novoCliente = $this->getBodyRequest();
        
        $clienteModel = $this->getModel('Cliente');

        $clienteObj = $clienteModel->create($novoCliente);

        if ($clienteObj) {
            http_response_code(201);
            echo json_encode(['cliente' => $clienteObj, 'message' => 'Cliente cadastrado com sucesso']);
        } else {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao cadastrar cliente']);
        }
    }

    public function update($id) {
        $atualizacaoCliente = $this->getBodyRequest();

        $clienteModel = $this->getModel('Cliente');
        $clienteAtualizado = $clienteModel->update($id, $atualizacaoCliente);

        if ($clienteAtualizado) {
            http_response_code(200);
            echo json_encode($clienteAtualizado);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Cliente não encontrado ou não atualizado']);
        }
    }
    
}