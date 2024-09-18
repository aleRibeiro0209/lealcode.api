<?php

namespace App\Controllers;

use App\Core\Controller;

class Veiculos extends Controller {

    public function index() {
        $solicitacaoPaginada = new \stdClass;
        $solicitacaoPaginada->limite = isset($_GET['limite']) && $_GET['limite'] != '' ? (int)$_GET['limite'] : 10;
        $solicitacaoPaginada->pagina = isset($_GET['pagina']) && $_GET['pagina'] != '' ? (int)$_GET['pagina'] : 1;
        $solicitacaoPaginada->offset = ($solicitacaoPaginada->pagina - 1) * $solicitacaoPaginada->limite;

        $veiculoModel = $this->getModel('Veiculo');
        $veiculoList = $veiculoModel->findAll($solicitacaoPaginada);
        
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
        $novoVeiculo->carroceria = $carroceriaModel->findId($novoVeiculo->carroceria);

        if ($novoVeiculo->carroceria) {
            $veiculoObj = $veiculoModel->create($novoVeiculo);
            if ($veiculoObj) {
                http_response_code(201);
                echo json_encode($veiculoObj);
            } else {
                http_response_code(500);
                echo json_encode(['erro' => 'Erro ao cadastrar veículo']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Carroceria não cadastrada ou não encontrada']);
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