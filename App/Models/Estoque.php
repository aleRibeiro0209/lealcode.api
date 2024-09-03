<?php

namespace App\Models;

use App\Core\Model;

class Estoque {
    
    public function findAll(): array {
        $sql = "SELECT * FROM tbEstoque";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    // TODO: Adicionar métodos ao Controller de Estoque
}
