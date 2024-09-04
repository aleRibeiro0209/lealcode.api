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

    public function update($id, $data) {
        
    }

    // TODO: Adicionar métodos ao Model de Estoque
}
