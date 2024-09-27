<?php

namespace App\Models;

use App\Core\Model;

class Notificacao {

    private int $idNotificacao;
    private string $mensagem;
    private \DateTime $dataHora;
    private int $idFuncionario;
    private int $idVeiculo;

    public function findAll($data) {
        $sql = "SELECT * FROM tbNotificacoes WHERE (dataHora BETWEEN :dataInicial AND :dataFinal OR (:dataInicial IS NULL AND :dataFinal IS NULL)) ORDER BY dataHora DESC";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindParam(':dataInicial', $data->dataInicial);
        $stmt->bindParam(':dataFinal', $data->dataFinal);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getId($id) {
        $sql = "SELECT * FROM tbNotificacoes WHERE idNotificacao = :id";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return $stmt->fetch(\PDO::FETCH_OBJ);
        } else {
            return null;
        }
    }

    public function create($data, int $idFuncionario) {
        $this->mensagem = $data->mensagem;
        $this->idFuncionario = $idFuncionario;

        $sql = "INSERT INTO tbNotificacoes (mensagem, dataHora, idFuncionario) VALUES (:mensagem, CURRENT_TIMESTAMP, :idFuncionario)";

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':mensagem', $this->mensagem);
            $stmt->bindParam(':idFuncionario', $this->idFuncionario);
            
            if ($stmt->execute()) {
                $this->idNotificacao = Model::getLastId('idNotificacao', 'tbNotificacoes');
                return $this;
            }

        } catch (\PDOException $e) {
            http_response_code(500);
            return null;
        }
    }

    public function veiculoTrigger(int $idFuncionario, int $idVeiculo) {
        $this->idFuncionario = $idFuncionario;
        $this->idVeiculo = $idVeiculo;
        $this->mensagem = $this->getMessageVeiculo();

        $sql = "INSERT INTO tbNotificacoes (mensagem, dataHora, idFuncionario, idVeiculo) VALUES (:mensagem, CURRENT_TIMESTAMP, :idFuncionario, :idVeiculo)";

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':mensagem', $this->mensagem);
            $stmt->bindParam(':idFuncionario', $this->idFuncionario);
            $stmt->bindParam(':idVeiculo', $this->idVeiculo);
            
            if ($stmt->execute()) {
                $this->idNotificacao = Model::getLastId('idNotificacao', 'tbNotificacoes');
                return $this;
            }

        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao registrar notificação.']);
            return null;
        }
    }

    public function delete($id): bool {
        $sql =  "DELETE FROM tbNotificacoes WHERE idNotificacao = :id";

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            return false;
        }
    }

    private function getMessageVeiculo() {
        return "O veículo de código " . $this->idVeiculo . " foi cadastrado com sucesso pelo funcionário de matrícula " . $this->idFuncionario . ".";
    }
}