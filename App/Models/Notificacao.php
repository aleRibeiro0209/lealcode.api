<?php

namespace App\Models;

use App\Core\Model;

class Notificacao {

    private int $idNotificacao;
    private string $mensagem;
    private \DateTime $dataHora;
    private int $idFuncionario;
    private int $idVeiculo;

    public function findAll() {
        $sql = "SELECT * FROM tbNotificacoes";

        $stmt = Model::getConn()->prepare($sql);
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

    // TODO: Criar método para chamar no store do veiculo, para criar um notificação

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
}