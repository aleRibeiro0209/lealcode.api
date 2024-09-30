<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\FileUploader;

class Funcionarios extends Controller {

    public function index() {
        $funcionarioModel = $this->getModel('Funcionario');
        $funcionariosList = $funcionarioModel->findAll();

        http_response_code(200);
        echo json_encode($funcionariosList);
    }

    public function show($id) {
        $funcionarioModel = $this->getModel('Funcionario');
        $funcionario = $funcionarioModel->getId($id);

        if ($funcionario) {
            http_response_code(200);
            echo json_encode($funcionario);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Funcionário não cadastrado']);
        }
    }

    public function store() {
        $novoFuncionario = $this->getBodyRequest();
        
        $funcionarioModel = $this->getModel('Funcionario');
        $cargoModel = $this->getModel('Cargo');
        $novoFuncionario->cargo = $cargoModel->findId($novoFuncionario->cargo);

        if($novoFuncionario->fileName && $novoFuncionario->fileData) {
            $uploader = new FileUploader('funcionarios/');

            $novoFuncionario->fotoPerfil = $uploader->uploadBase64File($novoFuncionario->fileData, $novoFuncionario->fileName, $novoFuncionario->fileType);
        }

        if ($novoFuncionario->cargo) {
            $funcionarioObj = $funcionarioModel->create($novoFuncionario);
            
            if ($funcionarioObj) {
                http_response_code(201);
                echo json_encode(['funcionario' => $funcionarioObj, 'message' => 'Funcionário cadastrado com sucesso']);
            } else {
                http_response_code(500);
                echo json_encode(['erro' => 'Erro ao cadastrar funcionário']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Cargo não cadastrado ou não encontrado']);
        }
    }

    public function update($id) {
        $atualizacaoFuncionario = $this->getBodyRequest();

        $funcionarioModel = $this->getModel('Funcionario');
        $cargoModel = $this->getModel('Cargo');
        $atualizacaoFuncionario->cargo = $cargoModel->findId($atualizacaoFuncionario->cargo);

        if($atualizacaoFuncionario->cargo) {
            $funcionarioAtualizado = $funcionarioModel->update($id, $atualizacaoFuncionario);

            if ($funcionarioAtualizado) {
                http_response_code(200);
                echo json_encode($funcionarioAtualizado);
            } else {
                http_response_code(404);
                echo json_encode(['erro' => 'Funcionário não encontrado ou não atualizado']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Cargo não cadastrada']);
        }
    }

    public function destroy($id) {
        $funcionarioModel = $this->getModel('Funcionario');
        $deleted = $funcionarioModel->delete($id);

        if($deleted) {
            http_response_code(200);
            echo json_encode(['success' => 'Funcionário deletado com sucesso']);
        } else {
            http_response_code(404);
            echo json_encode(['erro' => 'Funcionário não encontrado ou já deletado']);
        }
    }
}