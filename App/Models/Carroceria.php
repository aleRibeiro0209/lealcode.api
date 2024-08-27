<?php

namespace App\Models;

use App\Core\Model;

class Carroceria {

    private int $idCarroceria;
    private string $descricao;

    private function contructCarroceria($data): Carroceria {
        $this->descricao = $data->descricao;

        return $this;
    }

    public function findAll(): array {
        $sql = "SELECT * FROM tbCarrocerias";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getId($id) {
        $sql = "SELECT * FROM tbCarrocerias WHERE idCarroceria = ?";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();

        if($stmt->rowCount()) {
            return $stmt->fetch(\PDO::FETCH_OBJ);
        }

        return null;
    }

    public function findId($descricao) {
        $sql = "SELECT idCarroceria FROM tbCarrocerias WHERE descricao = ?";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindValue(1, $descricao);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return $stmt->fetchColumn();
        } else {
            return null;
        }
    }

    public function create($data): ?Carroceria {
        $this->contructCarroceria($data);

        $sql = "INSERT INTO tbCarrocerias (descricao) VALUES (:descricao)";

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':descricao', $this->descricao);

            if ($stmt->execute()) {
                $this->idCarroceria = Model::getLastId('idCarroceria', 'tbCarrocerias');
                return $this;
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Não foi possível inserir os dados da Carroceria: ' . $e->getMessage()]);
        }
    }

    public function update($id, $data) {
        $sql = "UPDATE tbCarrocerias SET descricao = :descricao WHERE idCarroceria = :id";

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':descricao', $data->descricao);
            $stmt->bindParam(':id', $id);

            if($stmt->execute()) {
                return $this->getId($id);
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao atualizar a carroceria: ' . $e->getMessage()]);
        }

        return null;
    }

    public function delete($id): bool {
        $sql =  "DELETE FROM tbCarrocerias WHERE idCarroceria = :id";

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao deletar a carroceria: ' . $e->getMessage()]);
            return false;
        }
    }

}