<?php

namespace App\Models;

use App\Core\Model;

class Cargo {

    private int $idCargo;
    private string $descricao;
    private string $permissoes;
    private int $idSetor;

    public function findAll(): array {
        $sql = "SELECT * FROM tbCargos";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function findId($descricao) {
        $sql = "SELECT idCargo FROM tbCargos WHERE descricao = ?";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindValue(1, $descricao);
        $stmt->execute();

        if ($stmt->rowCount()) {
            return $stmt->fetchColumn();
        } else {
            return null;
        }
    }

    public function getId($id) {
        $sql = "SELECT * FROM tbCargos WHERE idCargo = :id";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function create($data): ?Cargo {
        $this->descricao = $data->descricao;
        $this->permissoes = $data->permissoes;
        $this->idSetor = $data->setor;

        $sql = "INSERT INTO tbCargos (descricao, permissoes, idSetor) VALUES (:descricao, :permissoes, :idSetor)";

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(":descricao", $this->descricao);
            $stmt->bindParam(":permissoes", $this->permissoes);
            $stmt->bindParam(":idSetor", $this->idSetor);
            
            if ($stmt->execute()) {
                $this->idCargo = Model::getLastId('idCargo', 'tbCargos');
                return $this;
            }

        } catch (\PDOException $e) {
            http_response_code(500);
            return null;
        }

        return null;
    }

    public function update($id, $data) {
        $sql = "UPDATE tbCargos SET descricao = :descricao, permissoes = :permissoes, idSetor = :idSetor WHERE idCargo = :id";

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':descricao', $data->descricao);
            $stmt->bindParam(':permissoes', $data->permissoes);
            $stmt->bindParam(':idSetor', $data->setor);
            $stmt->bindParam(':id', $id);

            if($stmt->execute()) {
                return $this->getId($id);
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            return null;
        }

        return null;
    }

    public function delete($id): bool {
        $sql =  "DELETE FROM tbCargos WHERE idCargo = :id";

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