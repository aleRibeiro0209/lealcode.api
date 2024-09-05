<?php

namespace App\Models;

use App\Core\Model;

class Estoque {

    private int $idEstoque;
    private int $idVeiculo;
    private string $status;
    private int $idFuncionario;
    private string $dataAtualizacao;
    
    public function findAll(): array {
        $sql = "SELECT * FROM tbEstoque";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
    
    public function getId($id): array {
        $sql = "SELECT * FROM tbEstoque WHERE idEstoque = :id";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function update(int $id, int $idFuncionario, $data) {
        $sql = 'UPDATE tbEstoque SET status = :status, idFuncionario = :idFuncionario, dataAtualizacao = CURRENT_TIMESTAMP WHERE idEstoque = :id';

        try {
            $stmt = Model::getConn()->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':status', $data->status);
            $stmt->bindParam(':idFuncionario', $idFuncionario);

            if ($stmt->execute()) {
                return $this->getId($id);
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao atualizar o estoque: ' . $e->getMessage()]);
        }
    }

}
