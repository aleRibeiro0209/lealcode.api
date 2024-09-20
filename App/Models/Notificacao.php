<?php

namespace App\Models;

use App\Core\Model;

class Notificacao {

    private int $idNotificacao;
    private string $mensagem;
    private \DateTime $dataHora;

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

    public function create($data) {
        $this->mensagem = $data->mensagem;

        $sql = "INSERT INTO tbNotificacoes (mensagem, dataHora) VALUES (:mensagem, CURRENT_TIME)";

        $dataHoraFormatada = $this->dataHora->format('Y-m-d H:m:s');

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':mensagem', $this->mensagem);
            
            if ($stmt->execute()) {
                $this->idNotificacao = Model::getLastId('idNotificacao', 'tbNotificacoes');
                return $this;
            }

        } catch (\PDOException $e) {
            http_response_code(500);
            return null;
        }
    }

    public function update($id, $data) {
        $sql = "UPDATE tbNotificacoes SET mensagem = :mensagem, dataHora = CURRENT_TIME WHERE idNotificacao = :id";

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':mensagem', $data->mensagem);
            $stmt->bindParam(':id', $id);

            if($stmt->execute()) {
                return $this->getId($id);
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            return null;
        }
    }

    public function delete($id): bool {
        $sql =  "DELETE FROM tbNotificacoes WHERE idNotificacao = :id";

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (\PDOException $e) {
            http_response_code(500);
            return false;
        }
    }
}