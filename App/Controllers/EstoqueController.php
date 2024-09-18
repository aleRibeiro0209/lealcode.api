<?php

namespace App\Controllers;

use App\Core\Controller;

class EstoqueController extends Controller {

    public function index() {
        $solicitacaoPaginada = new \stdClass;
        $solicitacaoPaginada->limite = isset($_GET['limite']) && $_GET['limite'] != '' ? (int)$_GET['limite'] : 10;
        $solicitacaoPaginada->pagina = isset($_GET['pagina']) && $_GET['pagina'] != '' ? (int)$_GET['pagina'] : 1;
        $solicitacaoPaginada->offset = ($solicitacaoPaginada->pagina - 1) * $solicitacaoPaginada->limite;

        $estoqueModel = $this->getModel('Estoque');
        $estoqueList = $estoqueModel->findAll($solicitacaoPaginada);

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