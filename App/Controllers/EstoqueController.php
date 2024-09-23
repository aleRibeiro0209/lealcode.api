<?php

namespace App\Controllers;

use App\Core\Controller;

class EstoqueController extends Controller {

    public function index() {
        $solicitacaoParametrizada = new \stdClass;
        $solicitacaoParametrizada->ano = isset($_GET['ano']) && $_GET['ano'] != '' ? $_GET['ano'] : null;
        $solicitacaoParametrizada->modelo = isset($_GET['modelo']) && $_GET['modelo'] != '' ? $_GET['modelo'] : null;
        $solicitacaoParametrizada->cor = isset($_GET['cor']) && $_GET['cor'] != '' ? $_GET['cor'] : null;
        $solicitacaoParametrizada->placa = isset($_GET['placa']) && $_GET['placa'] != '' ? $_GET['placa'] : null;
        $solicitacaoParametrizada->idFuncionario = isset($_GET['idFuncionario']) && $_GET['idFuncionario'] != '' ? $_GET['idFuncionario'] : null;
        $solicitacaoParametrizada->status = isset($_GET['status']) && $_GET['status'] != '' ? $_GET['status'] : null;
        $solicitacaoParametrizada->dataAtualizacao = isset($_GET['dataAtualizacao']) && $_GET['dataAtualizacao'] != '' ? $_GET['dataAtualizacao'] : null;

        $solicitacaoParametrizada->limite = isset($_GET['limite']) && $_GET['limite'] != '' ? (int)$_GET['limite'] : 10;
        $solicitacaoParametrizada->pagina = isset($_GET['pagina']) && $_GET['pagina'] != '' ? (int)$_GET['pagina'] : 1;
        $solicitacaoParametrizada->offset = ($solicitacaoParametrizada->pagina - 1) * $solicitacaoParametrizada->limite;

        $estoqueModel = $this->getModel('Estoque');
        $estoqueList = $estoqueModel->findAll($solicitacaoParametrizada);

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

    public function update(int $id) {
        $atualizacaoEstoque = $this->getBodyRequest();
        $estoqueModel = $this->getModel('Estoque');
        $estoqueAtualizado = $estoqueModel->update($id, $this->funcionario->idFuncionario, $atualizacaoEstoque);

        if ($estoqueAtualizado) {
            http_response_code(200);
            echo json_encode(['message' => 'Registro atualizado com sucesso', 'data' => $estoqueAtualizado]);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Estoque não atualizado ou id não encontrado']);
        }
    }

}