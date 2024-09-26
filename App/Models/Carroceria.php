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

    public function findAll($data): array {
        if ($data->limite > 0) {
            // Consulta SQL com ordenação e paginação
            $sql = "SELECT * FROM tbCarrocerias ORDER BY descricao ASC LIMIT :limite OFFSET :offset";
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':limite', $data->limite, \PDO::PARAM_INT);
            $stmt->bindParam(':offset', $data->offset, \PDO::PARAM_INT);
        } else {
            // Consulta SQL sem limite e offset, retornando todos os registros
            $sql = "SELECT * FROM tbCarrocerias C WHERE (:carroceria IS NULL OR C.descricao LIKE CONCAT('%', :carroceria, '%')) ORDER BY C.descricao ASC";
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':carroceria', $data->carroceria);
        }
    
        // Executando a consulta
        $stmt->execute();
        $carrocerias = $stmt->fetchAll(\PDO::FETCH_OBJ);
    
        // Consulta para contar o total de registros
        $sqlCount = "SELECT COUNT(*) FROM tbCarrocerias";
        $total = Model::getConn()->query($sqlCount)->fetchColumn();
    
        // Retornando os dados de paginação e os resultados
        return [
            'carrocerias' => $carrocerias,
            'total' => $total,
            'paginaAtual' => $data->limite > 0 ? $data->pagina : null,
            'itensPorPagina' => $data->limite > 0 ? $data->limite : $total,
            'totalPaginas' => $data->limite > 0 ? ceil($total / $data->limite) : 1
        ];
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
            return null;
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
            return null;
        }
    }

    public function delete($id): bool {
        $sql =  "DELETE FROM tbCarrocerias WHERE idCarroceria = :id";

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