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
        $sql = "SELECT E.idEstoque, E.status, E.idFuncionario, DATE_FORMAT(E.dataAtualizacao, '%d/%m/%Y') as dataAtualizacao, V.modelo, V.ano, V.placa, V.cor FROM tbEstoque E INNER JOIN tbVeiculos V ON E.idVeiculo = V.idVeiculo LIMIT :limite OFFSET :offset";

        $stmt = Model::getConn()->prepare($sql);
        $stmt->bindParam(':limite', $data->limite, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $data->offset, \PDO::PARAM_INT);
        $stmt->execute();
        $estoque = $stmt->fetchAll(\PDO::FETCH_OBJ);

        $sqlCount = "SELECT COUNT(*) FROM tbVeiculos";
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
            echo json_encode(['erro' => 'Erro ao atualizar o estoque: ' . $e->getMessage()]);
        }
    }

}
