<?php

namespace App\Models;

use App\Core\Model;

class Estoque {

    private int $idEstoque;
    private int $idVeiculo;
    private string $status;
    private int $idFuncionario;
    private string $dataAtualizacao;
    
    public function findAll($data): array {
        $sql = "SELECT E.idEstoque, E.status, E.idFuncionario, DATE_FORMAT(E.dataAtualizacao, '%d/%m/%Y') as dataAtualizacao, V.modelo, V.ano, V.placa, V.cor 
        FROM tbEstoque E 
        INNER JOIN tbVeiculos V ON E.idVeiculo = V.idVeiculo
        WHERE (:ano IS NULL OR V.ano LIKE CONCAT('%', :ano, '%'))
            AND (:modelo IS NULL OR V.modelo LIKE CONCAT('%', :modelo, '%'))
            AND (:cor IS NULL OR V.cor LIKE CONCAT('%', :cor, '%'))
            AND (:placa IS NULL OR V.placa LIKE CONCAT('%', :placa, '%'))
            AND (:status IS NULL OR E.status LIKE CONCAT('%', :status, '%'))
            AND (:idFuncionario IS NULL OR E.idFuncionario LIKE CONCAT('%', :idFuncionario, '%'))
            AND (:dataAtualizacao IS NULL OR E.dataAtualizacao LIKE CONCAT('%', :dataAtualizacao, '%'))
        LIMIT :limite OFFSET :offset";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindParam(':ano', $data->ano);
        $stmt->bindParam(':modelo', $data->modelo);
        $stmt->bindParam(':cor', $data->cor);
        $stmt->bindParam(':placa', $data->placa);
        $stmt->bindParam(':status', $data->status);
        $stmt->bindParam(':idFuncionario', $data->idFuncionario);
        $stmt->bindParam(':dataAtualizacao', $data->dataAtualizacao);
        $stmt->bindParam(':limite', $data->limite, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $data->offset, \PDO::PARAM_INT);
        $stmt->execute();
        $estoque = $stmt->fetchAll(\PDO::FETCH_OBJ);

        $sqlCount = "SELECT COUNT(*) FROM tbEstoque";
        $total = Model::getConn()->query($sqlCount)->fetchColumn();

        return [
            'estoque' => $estoque,
            'total' => $total,
            'paginaAtual' => $data->pagina,
            'itensPorPagina' => $data->limite,
            'totalPaginas' => ceil($total / $data->limite)
        ];
    }
    
    public function getId($id) {
        $sql = "SELECT E.*, V.cor, V.modelo, V.placa, V.ano, DATE_FORMAT(E.dataAtualizacao, '%d/%m/%Y') as dataAtualizacaoF FROM tbEstoque E INNER JOIN tbVeiculos V ON E.idVeiculo = V.idVeiculo WHERE idEstoque = :id";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_OBJ);
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
            return null;
        }
    }

}
