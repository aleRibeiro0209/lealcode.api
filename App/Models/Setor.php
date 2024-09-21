<?php

namespace App\Models;

use App\Core\Model;

class Setor {

    private int $idSetor;
    private string $descricao;

    private function contructSetor($data): Setor {
        $this->descricao = $data->descricao;

        return $this;
    }

    public function findAll(): array {
        $sql = "SELECT * FROM tbSetores ORDER BY descricao ASC";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getId($id) {
        $sql = "SELECT * FROM tbSetores WHERE idSetor = ?";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();

        if($stmt->rowCount()) {
            return $stmt->fetch(\PDO::FETCH_OBJ);
        }

        return null;
    }

    public function findId($descricao) {
        $sql = "SELECT idSetor FROM tbSetores WHERE descricao = ?";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindValue(1, $descricao);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return $stmt->fetchColumn();
        } else {
            return null;
        }
    }

    public function create($data): ?Setor {
        $this->contructSetor($data);

        $sql = "INSERT INTO tbSetores (descricao) VALUES (:descricao)";

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':descricao', $this->descricao);

            if ($stmt->execute()) {
                $this->idSetor = Model::getLastId('idSetor', 'tbSetores');
                return $this;
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            return null;
        }
    }

    public function update($id, $data) {
        $sql = "UPDATE tbSetores SET descricao = :descricao WHERE idSetor = :id";

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':descricao', $data->descricao);
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
        $sql =  "DELETE FROM tbSetores WHERE idSetor = :id";

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