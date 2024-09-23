<?php

namespace App\Controllers;

use App\Core\Controller;

class Veiculos extends Controller {

    public function index() {
        $solicitacaoParametrizada = new \stdClass;
        $solicitacaoParametrizada->ano = isset($_GET['ano']) && $_GET['ano'] != '' ? $_GET['ano'] : null;
        $solicitacaoParametrizada->modelo = isset($_GET['modelo']) && $_GET['modelo'] != '' ? $_GET['modelo'] : null;
        $solicitacaoParametrizada->cor = isset($_GET['cor']) && $_GET['cor'] != '' ? $_GET['cor'] : null;
        $solicitacaoParametrizada->placa = isset($_GET['placa']) && $_GET['placa'] != '' ? $_GET['placa'] : null;
        $solicitacaoParametrizada->carroceria = isset($_GET['carroceria']) && $_GET['carroceria'] != '' ? $_GET['carroceria'] : null;
        $solicitacaoParametrizada->marca = isset($_GET['marca']) && $_GET['marca'] != '' ? $_GET['marca'] : null;

        $solicitacaoParametrizada->limite = isset($_GET['limite']) && $_GET['limite'] != '' ? (int)$_GET['limite'] : 10;
        $solicitacaoParametrizada->pagina = isset($_GET['pagina']) && $_GET['pagina'] != '' ? (int)$_GET['pagina'] : 1;
        $solicitacaoParametrizada->offset = ($solicitacaoParametrizada->pagina - 1) * $solicitacaoParametrizada->limite;

        $veiculoModel = $this->getModel('Veiculo');
        $veiculoList = $veiculoModel->findAll($solicitacaoParametrizada);
        
        http_response_code(200);
        echo json_encode($veiculoList);
    }

    public function show($id) {
        $veiculoModel = $this->getModel('Veiculo');
        $veiculo = $veiculoModel->getId($id);

        if ($veiculo) {
            http_response_code(200);
            echo json_encode($veiculo);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Veículo não cadastrado']);
        }
    }

    public function store() {
        $novoVeiculo = $this->getBodyRequest();
        
        $veiculoModel = $this->getModel('Veiculo');
        $carroceriaModel = $this->getModel('Carroceria');
        $marcaModel = $this->getModel('Marca');
        $notificacaoModel = $this->getModel('Notificacao');
        $novoVeiculo->carroceria = $carroceriaModel->findId($novoVeiculo->carroceria);
        $novoVeiculo->marca = $marcaModel->findId($novoVeiculo->marca);

        if ($novoVeiculo->carroceria && $novoVeiculo->marca) {
            $veiculoObj = $veiculoModel->create($novoVeiculo);
            $notificacaoModel->veiculoTrigger($this->funcionario->idFuncionario, $veiculoObj->idVeiculo);

            if ($veiculoObj) {
                http_response_code(201);
                echo json_encode(['veiculo' => $veiculoObj, 'message' => 'Veículo cadastrado com sucesso']);
            } else {
                http_response_code(500);
                echo json_encode(['erro' => 'Erro ao cadastrar veículo']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'A carroceria ou marca informada não está cadastrada. Verifique os dados e tente novamente.']);
        }
    }

    public function update($id) {
        $atualizacaoVeiculo = $this->getBodyRequest();

        $veiculoModel = $this->getModel('Veiculo');
        $carroceriaModel = $this->getModel('Carroceria');
        $atualizacaoVeiculo->carroceria = $carroceriaModel->findId($atualizacaoVeiculo->carroceria);

        if($atualizacaoVeiculo->carroceria) {
            $veiculoAtualizado = $veiculoModel->update($id, $atualizacaoVeiculo);

            if ($veiculoAtualizado) {
                http_response_code(200);
                echo json_encode($veiculoAtualizado);
            } else {
                http_response_code(404);
                echo json_encode(['erro' => 'Veículo não encontrado ou não atualizado']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Carroceria não cadastrada']);
        }
    }

    public function destroy($id) {
        $veiculoModel = $this->getModel('Veiculo');
        $deleted = $veiculoModel->delete($id);

        if($deleted) {
            http_response_code(200);
            echo json_encode(['success' => 'Veiculo deletado com sucesso']);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Veiculo não encontrado ou já deletado']);
        }
    }
    
}